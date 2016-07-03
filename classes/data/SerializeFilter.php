<?php

namespace classes\data;

use Yii;
use yii\base\Model;
use yii\db\QueryInterface;
use yii\web\Request;
use yii\web\Response;
use yii\base\ActionFilter;

/**
 * Description of SerializeFilter
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class SerializeFilter extends ActionFilter
{
    /**
     * @var array list of supported response formats. The keys are MIME types (e.g. `application/json`)
     * while the values are the corresponding formats (e.g. `html`, `json`) which must be supported
     * as declared in [[\yii\web\Response::formatters]].
     *
     * If this property is empty or not set, response format negotiation will be skipped.
     */
    public $formats = [
        'application/json' => 'json',
    ];
    /**
     * @var string Table alias for unaliased column
     */
    public $alias;
    /**
     * @var array Maping field from query param to database field
     */
    public $fieldMap = [];
    /**
     * @var Request the current request. If not set, the `request` application component will be used.
     */
    public $request;
    /**
     * @var Response the response to be sent. If not set, the `response` application component will be used.
     */
    public $response;
    /**
     * @var array expand field for serialize object
     */
    public $expands = [];
    /**
     * @var array except field for serialize object
     */
    public $excepts = [];
    /**
     * @var string 
     */
    public $expandParam = 'expands';
    /**
     * @var string key for meta data
     */
    public $metaEnvelope;
    /**
     * @var string key for total result
     */
    public $totalKey = 'total';
    /**
     * @var string Key for retured array data
     */
    public $dataEnvelope;
    /**
     * @var type Key for single data
     */
    public $singleEnvelope;

    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->request = $this->request ? : Yii::$app->getRequest();
        $this->response = $this->response ? : Yii::$app->getResponse();
    }

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if ($this->request->getMethod() === 'OPTIONS') {
            $options = ['GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'];
            $this->response->setStatusCode(405);
            $this->response
                ->getHeaders()->set('Allow', implode(', ', $options));
            return false;
        }
        /* @var $action \yii\base\Action */
        if ($action->controller !== $this->owner) {
            $action->controller->attachBehavior('dProxySerializer', new ProxySerializer([
                'serializer' => $this
            ]));
        }
        if ($this->expandParam && ($expands = $this->request->get($this->expandParam))) {
            $this->expands = array_merge(preg_split('/\s*,\s*/', $expands, -1, PREG_SPLIT_NO_EMPTY), $this->expands);
        }
        $this->negotiate($this->request, $this->response);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function afterAction($action, $result)
    {
        /* @var $action \yii\base\Action */
        $result = $this->serialize($result);
        $action->controller->detachBehavior('dProxySerializer');
        return $result;
    }

    /**
     * Negotiates the response format.
     * @param Request $request
     * @param Response $response
     */
    protected function negotiate($request, $response)
    {
        $types = $request->getAcceptableContentTypes();
        foreach ($types as $type => $params) {
            if (isset($this->formats[$type])) {
                $response->format = $this->formats[$type];
                $response->acceptMimeType = $type;
                $response->acceptParams = $params;
                return;
            }
        }
    }

    /**
     * Return it self
     * @return static
     */
    public function getSerializeFilter()
    {
        return $this;
    }

    /**
     * Serializes the given data into a format that can be easily turned into other formats.
     * This method mainly converts the objects of recognized types into array representation.
     * It will not do conversion for unknown object types or non-object data.
     * The default implementation will handle [[Model]] and [[DataProviderInterface]].
     * You may override this method to support more object types.
     * @param mixed $data the data to be serialized.
     * @return mixed the converted data.
     */
    public function serialize($data)
    {
        if ($data instanceof Model && $data->hasErrors()) {
            return $this->serializeModelError($data);
        } elseif ($data instanceof Model) {
            return $this->serializeModel($data);
        } elseif ($data instanceof QueryInterface) {
            return $this->serializeQuery($data);
        }
        return $data;
    }

    /**
     * Serialize model error
     * @param Model $model
     */
    protected function serializeModelError($model)
    {
        $this->response->setStatusCode(422, 'Data Validation Failed.');
        return $model->getFirstErrors();
    }

    /**
     * Serialize model error
     * @param Model $model
     */
    protected function serializeModel($model)
    {
        if ($this->request->getIsHead()) {
            return null;
        }
        $model = Helper::serializeObject($model, $this->expands, $this->excepts);
        return $this->formatModel($model, $this->getMetaObject());
    }

    /**
     * Serializes a query.
     * @param QueryInterface $query
     * @return array the array representation of the data provider.
     */
    protected function serializeQuery($query)
    {
        if ($this->alias === null) {
            $this->alias = Helper::resolveAlias($query);
        }

        if (($sorting = $this->getSorting()) !== false) {
            $query->orderBy($sorting);
        }
        $meta = [];
        if (($pagination = $this->getPagination()) !== false) {
            list($limit, $page) = $pagination;
            $total = $query->count();
            $query->limit($limit)->offset(($page - 1) * $limit);
            $meta = [
                'total' => $total,
                'perSize' => $limit,
                'currentPage' => $page,
            ];
            $this->addPaginationHeader($meta);
        }

        if ($this->request->getIsHead()) {
            return null;
        }
        $models = Helper::serializeModels($query->all(), $this->expands, $this->excepts);
        return $this->formatModels($models, $this->getMetaObject($meta));
    }

    /**
     * Send pagination headers
     * @param array $meta
     */
    protected function addPaginationHeader($meta)
    {
        $this->response->getHeaders()
            ->add('X-Pagination-Total-Count', $meta['total'])
            ->add('X-Pagination-Per-Page', $meta['perSize'])
            ->add('X-Pagination-Current-Page', $meta['currentPage']);
        if ($meta['perSize'] < 1) {
            $pageCount = $meta['total'] > 0 ? 1 : 0;
        } else {
            $pageCount = (int) ((($meta['total'] > 0 ? (int) $meta['total'] : 0) + $meta['perSize'] - 1) / $meta['perSize']);
        }
        $this->response->getHeaders()->add('X-Pagination-Total-Page', $pageCount);
    }

    /**
     * Get meta object
     * @param array $values
     * @return array
     */
    protected function getMetaObject(array $values = [])
    {
        return array_merge(['status' => 'success'], $values);
    }

    /**
     * Format returned array model
     * @param array $model
     * @param array $meta
     * @return array
     */
    protected function formatModel($model, array $meta = [])
    {
        if ($this->singleEnvelope) {
            if ($this->metaEnvelope && !empty($meta)) {
                $result = [$this->metaEnvelope = $meta];
            } else {
                $result = $meta;
            }
            return array_merge($result, [$this->singleEnvelope => $model]);
        }
        return $model;
    }

    /**
     * Format returned array model
     * @param array $models
     * @param array $meta
     * @return array
     */
    protected function formatModels($models, array $meta = [])
    {
        if ($this->dataEnvelope) {
            if ($this->metaEnvelope && !empty($meta)) {
                $result = [$this->metaEnvelope = $meta];
            } else {
                $result = $meta;
            }
            return array_merge($result, [$this->dataEnvelope => $models]);
        }

        return $models;
    }

    /**
     * Get limit offset
     * @return array|boolean
     */
    protected function getPagination()
    {
        return false;
    }

    /**
     * Get sorting
     * @return array|boolean
     */
    protected function getSorting()
    {
        return false;
    }
}
