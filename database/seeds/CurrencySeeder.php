<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $create_table   =   "CREATE TABLE `currency_all` (
                            `id` int(11)  unsigned NOT NULL AUTO_INCREMENT,
                            `currency_code` varchar(191) NOT NULL,
                            `currency_name` varchar(191) NOT NULL,
                            `symbol` varchar(191) DEFAULT NULL,
                            `currency_country` varchar(191) DEFAULT NULL,
                            PRIMARY KEY (`id`)
                                                    ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=250;";

        $table_data     =   "INSERT INTO `currency_all` (`id`, `currency_code`, `currency_name`, `symbol`, `currency_country`) VALUES
(2, 'AED', 'United Arab Emirates Dirham', 'د.إ', 'United Arab Emirates Dirham'),
(3, 'AFN', 'Afghanistan Afghani', 'Af', 'Afghanistan Afghani'),
(4, 'ALL', 'Albania Lek', 'L', 'Albania Lek'),
(5, 'AMD', 'Armenia Dram', 'Դ', 'Armenia Dram'),
(6, 'ANG', 'Netherlands Antilles Guilder', 'ƒ', 'Netherlands Antilles Guilder'),
(7, 'AOA', 'Angola Kwanza', 'Kz', 'Angola Kwanza'),
(8, 'ARS', 'Argentina Peso', '$', 'Argentina Peso'),
(9, 'AUD', 'Australia Dollar', '$', ''),
(10, 'AWG', 'Aruba Guilder', 'ƒ', 'Aruba Guilder'),
(11, 'AZN', 'Azerbaijan New Manat', 'ман', 'Azerbaijan New Manat'),
(12, 'BAM', 'Bosnia and Herzegovina Convertible Marka', 'KM', 'Bosnia and Herzegovina Convertible Marka'),
(13, 'BBD', 'Barbados Dollar', '$', 'Barbados Dollar'),
(14, 'BDT', 'Bangladesh Taka', 'ó', 'Taka'),
(15, 'BGN', 'Bulgaria Lev', 'лв', ''),
(16, 'BHD', 'Bahrain Dinar', 'ب.د', 'dinar'),
(17, 'BIF', 'Burundi Franc', '₣', 'franc'),
(18, 'BMD', 'Bermuda Dollar', '$', 'Bermuda Dollar'),
(19, 'BND', 'Brunei Darussalam Dollar', '$', 'Brunei Darussalam Dollar'),
(20, 'BOB', 'Bolivia Boliviano', 'Bs.', 'Bolivia Boliviano'),
(21, 'BRL', 'Brazil Real', 'R$', 'Brazil Real'),
(22, 'BSD', 'Bahamas Dollar', '$', 'Bahamas Dollar'),
(23, 'BTN', 'Bhutan Ngultrum', 'Nu.', 'Bhutan Ngultrum'),
(24, 'BWP', 'Botswana Pula', 'P', 'Botswana Pula'),
(25, 'BYR', 'Belarus Ruble', 'Br', ''),
(26, 'BZD', 'Belize Dollar', 'BZ$', ''),
(27, 'CAD', 'Canada Dollar', '$', ''),
(28, 'CDF', 'Congo/Kinshasa Franc', '₣', ''),
(29, 'CHF', 'Switzerland Franc', 'CHF', ''),
(30, 'CLP', 'Chile Peso', '$', ''),
(31, 'CNY', 'Chinese Yuan Renminbi', '¥', 'Chinese Yuan Renminbi'),
(32, 'COP', 'Colombia Peso', '$', ''),
(33, 'CRC', 'Costa Rica Colon', '₡', ''),
(34, 'CUC', 'Cuba Convertible Peso', 'CUC$', ''),
(35, 'CUP', 'Cuba Peso', '$', ''),
(36, 'CVE', 'Cape Verde Escudo', '$', ''),
(37, 'CZK', 'Czech Republic Koruna', 'Kč', ''),
(38, 'DJF', 'Djibouti Franc', '₣', ''),
(39, 'DKK', 'Denmark Krone', 'kr', ''),
(40, 'DOP', 'Dominican Republic Peso', 'RD$', ''),
(41, 'DZD', 'Algeria Dinar', 'د.ج', ''),
(42, 'EGP', 'Egypt Pound', '£', ''),
(43, 'ERN', 'Eritrea Nakfa', 'Nfk', ''),
(44, 'ETB', 'Ethiopia Birr', 'ብር', ''),
(45, 'EUR', 'Euro Member Countries', '€', ''),
(46, 'FJD', 'Fiji Dollar', '$', ''),
(47, 'FKP', 'Falkland Islands Malvinas Pound', '£', ''),
(48, 'GBP', 'United Kingdom Pound', '£', 'pound'),
(49, 'GEL', 'Georgia Lari', ' ‎₾', ''),
(50, 'GGP', 'Guernsey Pound', '£', ''),
(51, 'GHS', 'Ghana Cedi', '¢', ''),
(52, 'GIP', 'Gibraltar Pound', '£', ''),
(53, 'GMD', 'Gambia Dalasi', 'D', ''),
(54, 'GNF', 'Guinea Franc', '₣  ', ''),
(55, 'GTQ', 'Guatemala Quetzal', 'Q', ''),
(56, 'GYD', 'Guyana Dollar', '$', ''),
(57, 'HKD', 'Hong Kong Dollar', '$', ''),
(58, 'HNL', 'Honduras Lempira', 'L', ''),
(59, 'HRK', 'Croatia Kuna', 'kn', ''),
(60, 'HTG', 'Haiti Gourde', 'G', ''),
(61, 'HUF', 'Hungary Forint', 'Ft', ''),
(62, 'IDR', 'Indonesia Rupiah', 'Rp', ''),
(63, 'ILS', 'Israel Shekel', '₪', ''),
(64, 'IMP', 'Isle of Man Pound', '£', ''),
(65, 'INR', 'India Rupee', '₨', 'Indian Rupee'),
(66, 'IQD', 'Iraq Dinar', 'ع.د', ''),
(67, 'IRR', 'Iran Rial', '﷼', ''),
(68, 'ISK', 'Iceland Krona', 'kr', ''),
(69, 'JEP', 'Jersey Pound', '£', ''),
(70, 'JMD', 'Jamaica Dollar', 'J$', ''),
(71, 'JOD', 'Jordan Dinar', 'د.ا', ''),
(72, 'JPY', 'Japan Yen', '¥', ''),
(73, 'KES', 'Kenya Shilling', 'Sh', ''),
(74, 'KGS', 'Kyrgyzstan Som', 'Лв', ''),
(75, 'KHR', 'Cambodia Riel', '៛', ''),
(76, 'KMF', 'Comoros Franc', 'CF', ''),
(77, 'KPW', 'Korea North Won', '₩', ''),
(78, 'KRW', 'Korea South Won', '₩', ''),
(79, 'KWD', 'Kuwait Dinar', 'د.ك', ''),
(80, 'KYD', 'Cayman Islands Dollar', '$', ''),
(81, 'KZT', 'Kazakhstan Tenge', '〒', ''),
(82, 'LAK', 'Laos Kip', '₭', ''),
(83, 'LBP', 'Lebanon Pound', 'ل.ل', ''),
(84, 'LKR', 'Sri Lanka Rupee', 'Rs', ''),
(85, 'LRD', 'Liberia Dollar', '$', ''),
(86, 'LSL', 'Lesotho Loti', 'L', ''),
(87, 'LYD', 'Libya Dinar', 'ل.د', ''),
(88, 'MAD', 'Morocco Dirham', 'د.م.', ''),
(89, 'MDL', 'Moldova Leu', 'L', ''),
(90, 'MGA', 'Madagascar Ariary', 'Ar', ''),
(91, 'MKD', 'Macedonia Denar', 'ден', ''),
(92, 'MMK', 'Myanmar Burma Kyat', 'K', ''),
(93, 'MNT', 'Mongolia Tughrik', '₮', ''),
(94, 'MOP', 'Macau Pataca', 'P', ''),
(95, 'MRO', 'Mauritania Ouguiya', 'UM', ''),
(96, 'MUR', 'Mauritius Rupee', '₨', ''),
(97, 'MVR', 'Maldives Maldive Islands Rufiyaa', 'ރ.', ''),
(98, 'MWK', 'Malawi Kwacha', 'MK', ''),
(99, 'MXN', 'Mexico Peso', '$', ''),
(100, 'MXP', 'Old Mexican Peso', '$', 'peso'),
(101, 'MYR', 'Malaysia Ringgit', 'RM', ''),
(102, 'MZN', 'Mozambique Metical', 'MTn', ''),
(103, 'NAD', 'Namibia Dollar', '$', ''),
(104, 'NGN', 'Nigeria Naira', '₦', 'Nigeria Naira'),
(105, 'NIO', 'Nicaragua Cordoba', 'C$', ''),
(106, 'NOK', 'Norway Krone', 'kr', ''),
(107, 'NPR', 'Nepal Rupee', '₨', ''),
(108, 'NZD', 'New Zealand Dollar', '$', ''),
(109, 'OMR', 'Oman Rial', 'ر.ع.', ''),
(110, 'PAB', 'Panama Balboa', 'B/.', ''),
(111, 'PEN', 'Peru Nuevo Sol', 'S/.', ''),
(112, 'PGK', 'Papua New Guinea Kina', 'K', ''),
(113, 'PHP', 'Philippines Peso', 'PHP', ''),
(114, 'PKR', 'Pakistan Rupee', '₨', ''),
(115, 'PLN', 'Poland Zloty', 'z?', ''),
(116, 'PYG', 'Paraguay Guarani', 'Gs', ''),
(117, 'QAR', 'Qatar Riyal', 'ر.ق', ''),
(118, 'RON', 'Romania New Leu', 'lei', ''),
(119, 'RSD', 'Serbia Dinar', 'din', ''),
(120, 'RUB', 'Russia Ruble', 'р.', ''),
(121, 'RUR', 'Old Russian Ruble', '₽', ''),
(122, 'RWF', 'Rwanda Franc', '₣', ''),
(123, 'SAR', 'Saudi Arabia Riyal', 'ر.س', ''),
(124, 'SBD', 'Solomon Islands Dollar', '$', ''),
(125, 'SCR', 'Seychelles Rupee', '₨', ''),
(126, 'SDG', 'Sudan Pound', '£', ''),
(127, 'SEK', 'Sweden Krona', 'kr', ''),
(128, 'SGD', 'Singapore Dollar', '$', ''),
(129, 'SHP', 'Saint Helena Pound', '£', ''),
(130, 'SLL', 'Sierra Leone Leone', 'Le', ''),
(131, 'SOS', 'Somalia Shilling', 'S', ''),
(132, 'SPL', 'Seborga Luigino', '€', ''),
(133, 'SRD', 'Suriname Dollar', '$', ''),
(134, 'STD', 'São Tomé and Príncipe Dobra', 'Db', ''),
(135, 'SVC', 'El Salvador Colon', '$', ''),
(136, 'SYP', 'Syria Pound', 'ل.س', ''),
(137, 'SZL', 'Swaziland Lilangeni', 'L', ''),
(138, 'THB', 'Thailand Baht', '฿', ''),
(139, 'TJS', 'Tajikistan Somoni', 'ЅМ', ''),
(140, 'TMT', 'Turkmenistan Manat', 'm', ''),
(141, 'TND', 'Tunisia Dinar', 'د.ت', ''),
(142, 'TOP', 'Tonga Pa\'anga', 'T$', ''),
(143, 'TRY', 'Turkey Lira', '₤', ''),
(144, 'TTD', 'Trinidad and Tobago Dollar', 'TT$', ''),
(145, 'TVD', 'Tuvalu Dollar', '$', ''),
(146, 'TWD', 'Taiwan New Dollar', 'NT$', ''),
(147, 'TZS', 'Tanzania Shilling', 'Sh', ''),
(148, 'UAH', 'Ukraine Hryvnia', '₴', ''),
(149, 'UGX', 'Uganda Shilling', 'Sh', ''),
(150, 'USD', 'United States Dollar', '$', 'US Dollar'),
(151, 'UYU', 'Uruguay Peso', '$', ''),
(152, 'UZS', 'Uzbekistan Som', 'som', ''),
(153, 'VEF', 'Venezuela Bolivar', 'Bs', ''),
(154, 'VND', 'Viet Nam Dong', '₫', 'Viet Nam Dong'),
(155, 'VUV', 'Vanuatu Vatu', 'Vt', ''),
(156, 'WST', 'Samoa Tala', 'T', ''),
(157, 'XAF', 'Communauté Financière Africaine BEAC CFA Franc BEAC', '₣', ''),
(158, 'XCD', 'East Caribbean Dollar', '$', ''),
(159, 'XDR', 'International Monetary Fund IMF Special Drawing Rights', 'SDR', ''),
(160, 'XOF', 'Communauté Financière Africaine BCEAO Franc', 'CFA', ''),
(161, 'XPF', 'Comptoirs Français du Pacifique CFP Franc', '₣', ''),
(162, 'YER', 'Yemen Rial', '﷼', ''),
(163, 'ZAR', 'South Africa Rand', 'R', ''),
(164, 'ZMW', 'Zambia Kwacha', 'ZK', ''),
(165, 'ZWD', 'Zimbabwe Dollar', 'Z$', '')";

        if(!Schema::hasTable('currency_all')){
            DB::statement($create_table);
            DB::statement($table_data);
        }
    }
}