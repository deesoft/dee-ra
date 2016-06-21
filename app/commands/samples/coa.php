<?php
return [
    ["10000000", "AKTIVA", 'N', "D", 'items' => [
            ["11000000", "AKTIVA LANCAR", 'N', "D", 'items' => [
                    ["11100000", "Kas", 'N', "D", 'items' => [
                            ["11100001", "Kas Pusat", 'N', "D"],
                            ["11100002", "Kas Unit Lubeg", 'N', "D"],
                            ["11100003", "Kas Unit Permindo", 'N', "D"],
                            ["11100004", "Kas Unit Bukittinggi", 'N', "D"],
                        ]],
                    ["11200000", "Bank", 'N', "D", 'items' => [
                            ["11200001", "Rek. Penampung BCA", 'N', "D"],
                            ["11200002", "Rek. Penampung Mandiri", 'N', "D"],
                        ]
                    ],
                    ["11300000", "Piutang", 'N', "D", 'items' => [
                            ["11300001", "Piutang Dagang", 'N', "D"],
                            ["11300002", "Piutang Dagang Belum Difakturkan", 'N', "D"],
                        ]
                    ],
                    ["11400000", "Persediaan Barang", 'N', "D"],
                    ["11500000", "Sewa dibayar dimuka", 'N', "D"],
                ]
            ],
            ["12000000", "AKTIVA TETAP", 'N', "D", 'items' => [
                    ["12000001", "Peralatan", 'N', "D"],
                    ["12000002", "Akumulasi Penyusutan Peralatan", 'N', "K"],
                ]
            ],
        ]
    ],
    ["20000000", "KEWAJIBAN", 'N', "K", 'items' => [
            ["21000000", "HUTANG LANCAR", 'N', "K", 'items' => [
                    ["21000001", "Hutang Dagang", 'N', "K"],
                    ["21000002", "Hutang Gaji", 'N', "K"],
                    ["21000003", "Hutang Sewa", 'N', "K"],
                    ["21000004", "Hutang Pajak Penghasilan", 'N', "K"],
                    ["21000005", "Hutang Dagang Belum Difakturkan", 'N', "K"],
                ]
            ],
            ["22000000", "HUTANG JANGKA PANJANG", 'N', "K"],
        ]
    ],
    ["30000000", "MODAL", 'N', "K", 'items' => [
            ["31000001", "Modal Pemilik", 'N', "K"],
            ["31000002", "Prive", 'N', "K"],
        ]
    ],
    ["40000000", "PENDAPATAN", 'R', "K", 'items' => [
            ["41000001", "Penjualan", 'R', "K"],
            ["41000002", "Retur Penjualan", 'R', "D"],
            ["41000003", "Diskon Penjualan", 'R', "D"],
        ]
    ],
    ["50000000", "HPP & BIAYA LANGSUNG", 'R', "K", 'items' => [
            ["51000001", "HPP/Pembelian", 'R', "D"],
            ["51000002", "Ongkos Angkut Barang", 'R', "K"],
            ["51000003", "Diskon Pembelian", 'R', "K"],
        ]
    ],
    ["60000000", "BIAYA", 'R', "D", 'items' => [
            ["61000001", "Beban Gaji/ Upah", 'R', "D"],
            ["61000002", "Beban Adm & Umum", 'R', "D"],
            ["61000003", "Beban Sewa Gedung", 'R', "D"],
            ["61000004", "Beban Penyesuaian Persediaan", 'R', "D"],
            ["61000005", "Beban Penyusutan Peralatan", 'R', "D"],
            ["61000006", "Beban Bunga", 'R', "D"],
            ["61000007", "Beban Listrik & Telepon", 'R', "D"],
            ["61000008", "Potongan CC BCA", 'R', "D"],
            ["61000009", "Potongan CC Mandiri", 'R', "D"],
        ]
    ],
];
