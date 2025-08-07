<?php

class Admin
{
    protected $lang;
    protected $accessPage = [];
    protected $user;
    protected $userInfo;
    protected $db;
    protected $urlPag;
    protected $page;
    protected $limit;
    protected $start; // початкова позиція пагінації
    protected $settings = [];
    protected $body_class = []; // класи тега body
    protected $pagination = false; // переменная для вывода пагинации
    protected $function;

    protected $lang_id;
    protected $meta_title = 'Digital Game';
    protected $styles = [];
    protected $scripts = [];
    protected $scripts_after = [];
    // protected $orderModelObject;

    /*private $countries = [
        ['en'=>'Afghanistan','no'=>'Afghanistan','code'=>'93','pos'=>'0 -22px'],
        ['en'=>'Albania','no'=>'Albania','code'=>'355','pos'=>'0 -55px'],
        ['en'=>'Algeria','no'=>'Algerie','code'=>'213','pos'=>'-16px -77px'],
        ['en'=>'American Samoa','no'=>'Amerikansk Samoa','code'=>'1-684','pos'=>'0 -110px'],
        // ['en'=>'Andorra','no'=>'','code'=>'376','pos'=>'0 0'],
        ['en'=>'Angola','no'=>'Angola','code'=>'244','pos'=>'0 -88px'],
        ['en'=>'Anguilla','no'=>'Anguilla','code'=>'1-264','pos'=>'0 -44px'],
        // ['en'=>'Antarctica','no'=>'','code'=>'672','pos'=>'0 0'],
        ['en'=>'Antigua and Barbuda','no'=>'Antigua and Barbuda','code'=>'1-268','pos'=>'0 -33px'],
        ['en'=>'Argentina','no'=>'Argentina','code'=>'54','pos'=>'0 -99px'],
        ['en'=>'Armenia','no'=>'Armenia','code'=>'374','pos'=>'0 -66px'],
        ['en'=>'Aruba','no'=>'Aruba','code'=>'297','pos'=>'0 -143px'],
        ['en'=>'Australia','no'=>'Australia','code'=>'61','pos'=>'0 -132px'],
        ['en'=>'Austria','no'=>'Østerrike','code'=>'43','pos'=>'0 -121px'],
        ['en'=>'Azerbaijan','no'=>'Aserbajdsjan','code'=>'1-242','pos'=>'0 -165px'],
        ['en'=>'Bahamas','no'=>'Bahamas','code'=>'355','pos'=>'0 -319px'],
        ['en'=>'Bahrain','no'=>'Bahrain','code'=>'973','pos'=>'0 -242px'],
        ['en'=>'Bangladesh','no'=>'Bangladesh','code'=>'880','pos'=>'0 -198px'],
        ['en'=>'Barbados','no'=>'Barbados','code'=>'1-246','pos'=>'0 -187px'],
        ['en'=>'Belarus','no'=>'Hviterussland','code'=>'375','pos'=>'0 -363px'],
        ['en'=>'Belgium','no'=>'Belgia','code'=>'32','pos'=>'0 -209px'],
        ['en'=>'Belize','no'=>'Belize','code'=>'501','pos'=>'0 -374px'],
        ['en'=>'Benin','no'=>'Benin','code'=>'229','pos'=>'0 -264px'],
        ['en'=>'Bermuda','no'=>'Bermuda','code'=>'1-441','pos'=>'0 -275px'],
        ['en'=>'Bhutan','no'=>'Bhutan','code'=>'975','pos'=>'0 -330px'],
        ['en'=>'Bolivia','no'=>'Bolivia','code'=>'591','pos'=>'0 -297px'],
        ['en'=>'Bosnia and Herzegovina','no'=>'Bosnia and Hercegovina','code'=>'387','pos'=>'0 -176px'],
        ['en'=>'Botswana','no'=>'Botswana','code'=>'267','pos'=>'0 -352px'],
        ['en'=>'Brazil','no'=>'Brasil','code'=>'55','pos'=>'0 -308px'],
        ['en'=>'British Indian Ocean Territory','no'=>'Diego Garcia','code'=>'246','pos'=>'-16px -572px'],
        ['en'=>'British Virgin Islands','no'=>'De britiske jomfruøyene','code'=>'1-284','pos'=>'-64px -253px'],
        ['en'=>'Brunei','no'=>'Brunei','code'=>'673','pos'=>'0 -286px'],
        ['en'=>'Bulgaria','no'=>'Bulgaria','code'=>'359','pos'=>'0 -231px'],
        ['en'=>'Burkina Faso','no'=>'Burkina Faso','code'=>'226','pos'=>'0 -220px'],
        ['en'=>'Burundi','no'=>'Burundi','code'=>'257','pos'=>'0 -253px'],
        ['en'=>'Cambodia','no'=>'Kambodia','code'=>'855','pos'=>'-32px -99px'],
        ['en'=>'Cameroon','no'=>'Kamerun','code'=>'237','pos'=>'0 -495px'],
        ['en'=>'Canada','no'=>'Canada','code'=>'1','pos'=>'0 -385px'],
        ['en'=>'Cape Verde','no'=>'Kapp Verde-øyene','code'=>'238','pos'=>'0 -561px'],
        ['en'=>'Cayman Islands','no'=>'Caymanøyene','code'=>'1-345','pos'=>'-32px -176px'],
        ['en'=>'Central African Republic','no'=>'Den Sentralafrikanske Rep.','code'=>'236','pos'=>'0 -429px'],
        ['en'=>'Chad','no'=>'Tsjad','code'=>'235','pos'=>'-48px -572px'],
        ['en'=>'Chile','no'=>'Chile','code'=>'56','pos'=>'0 -484px'],
        ['en'=>'China','no'=>'Kina (folkerep.)','code'=>'86','pos'=>'0 -506px'],
        ['en'=>'Christmas Island','no'=>'Juleøya','code'=>'61','pos'=>'0 -572px'],
        ['en'=>'Cocos Islands','no'=>'Cocos Islands','code'=>'61','pos'=>'0 -407px'],
        ['en'=>'Colombia','no'=>'Colombia','code'=>'57','pos'=>'0 -517px'],
        ['en'=>'Comoros','no'=>'Komorene','code'=>'269','pos'=>'-32px -121px'],
        ['en'=>'Cook Islands','no'=>'Cook-øyene','code'=>'682','pos'=>'0 -473px'],
        ['en'=>'Costa Rica','no'=>'Costa Rica','code'=>'506','pos'=>'0 -539px'],
        ['en'=>'Croatia','no'=>'Kroatia','code'=>'385','pos'=>'-16px -495px'],
        ['en'=>'Cuba','no'=>'Cuba','code'=>'53','pos'=>'0 -528px'],
        // ['en'=>'Curacao','no'=>'','code'=>'599','pos'=>'0 0'],
        ['en'=>'Cyprus','no'=>'Kypros','code'=>'357','pos'=>'-16px 0'],
        ['en'=>'Czech Republic','no'=>'Tsjekkia','code'=>'420','pos'=>'-16px -11px'],
        ['en'=>'Democratic Republic of the Congo','no'=>'Den Demokratiske Republikk Kongo','code'=>'243','pos'=>'0 -418px'],
        ['en'=>'Denmark','no'=>'Danmark','code'=>'45','pos'=>'-16px -44px'],
        ['en'=>'Djibouti','no'=>'Djibouti','code'=>'253','pos'=>'-16px -33px'],
        ['en'=>'Dominica','no'=>'Dominica','code'=>'1-767','pos'=>'-16px -55px'],
        ['en'=>'Dominican Republic','no'=>'Den dominikanske republikk','code'=>'1-809, 1-829, 1-849','pos'=>'-16px -66px'],
        ['en'=>'East Timor','no'=>'Øst-Timor','code'=>'670','pos'=>'-64px -55px'],
        ['en'=>'Ecuador','no'=>'Ecuador','code'=>'593','pos'=>'-16px -88px'],
        ['en'=>'Egypt','no'=>'Egypt','code'=>'20','pos'=>'-16px -110px'],
        ['en'=>'El Salvador','no'=>'El Salvador','code'=>'503','pos'=>'-48px -528px'],
        ['en'=>'Equatorial Guinea','no'=>'Ekvatorial-Guinea','code'=>'240','pos'=>'-16px -385px'],
        ['en'=>'Eritrea','no'=>'Eritrea','code'=>'291','pos'=>'-16px -143px'],
        ['en'=>'Estonia','no'=>'Estland','code'=>'372','pos'=>'-16px -99px'],
        ['en'=>'Ethiopia','no'=>'Etiopia','code'=>'251','pos'=>'-16px -165px'],
        ['en'=>'Falkland Islands','no'=>'Falklandsøyene','code'=>'500','pos'=>'-16px -220px'],
        ['en'=>'Faroe Islands','no'=>'Færøyene','code'=>'298','pos'=>'-16px -242px'],
        ['en'=>'Fiji','no'=>'Fiji','code'=>'679','pos'=>'-16px -209px'],
        ['en'=>'Finland','no'=>'Finland','code'=>'358','pos'=>'-16px -198px'],
        ['en'=>'France','no'=>'Frankrike','code'=>'33','pos'=>'-16px -253px'],
        ['en'=>'French Polynesia','no'=>'Fransk Polynesia','code'=>'689','pos'=>'-48px -143px'],
        ['en'=>'Gabon','no'=>'Gabon','code'=>'241','pos'=>'-16px -264px'],
        ['en'=>'Gambia','no'=>'Gambia','code'=>'220','pos'=>'-16px -352px'],
        ['en'=>'Georgia','no'=>'Georgia','code'=>'995','pos'=>'-16px -297px'],
        ['en'=>'Germany','no'=>'Tyskland','code'=>'49','pos'=>'-16px -22px'],
        ['en'=>'Ghana','no'=>'Ghana','code'=>'233','pos'=>'-16px -319px'],
        ['en'=>'Gibraltar','no'=>'Gibraltar','code'=>'350','pos'=>'-16px -330px'],
        ['en'=>'Greece','no'=>'Hellas','code'=>'30','pos'=>'-16px -396px'],
        ['en'=>'Greenland','no'=>'Grønland','code'=>'299','pos'=>'-16px -341px'],
        ['en'=>'Grenada','no'=>'Grenada','code'=>'1-473','pos'=>'-16px -286px'],
        ['en'=>'Guam','no'=>'Guam','code'=>'1-671','pos'=>'-16px -429px'],
        ['en'=>'Guatemala','no'=>'Guatemala','code'=>'502','pos'=>'-16px -418px'],
        // ['en'=>'Guernsey','no'=>'','code'=>'44-1481','pos'=>'0 0'],
        ['en'=>'Guinea','no'=>'Guinea','code'=>'224','pos'=>'-16px -363px'],
        ['en'=>'Guinea-Bissau','no'=>'Guinea Bissau','code'=>'245','pos'=>'-16px -440px'],
        ['en'=>'Guyana','no'=>'Guyana','code'=>'592','pos'=>'-16px -451px'],
        ['en'=>'Haiti','no'=>'Haiti','code'=>'509','pos'=>'-16px -506px'],
        ['en'=>'Honduras','no'=>'Honduras','code'=>'504','pos'=>'-16px -484px'],
        ['en'=>'Hong Kong','no'=>'Hong Kong','code'=>'852','pos'=>'-16px -462px'],
        ['en'=>'Hungary','no'=>'Ungarn','code'=>'36','pos'=>'-16px -517px'],
        ['en'=>'Iceland','no'=>'Island','code'=>'354','pos'=>'-32px -22px'],
        ['en'=>'India','no'=>'India','code'=>'91','pos'=>'-16px -561px'],
        ['en'=>'Indonesia','no'=>'Indonesia','code'=>'62','pos'=>'-16px -528px'],
        ['en'=>'Iran','no'=>'Iran','code'=>'98','pos'=>'-32px -11px'],
        ['en'=>'Iraq','no'=>'Irak','code'=>'964','pos'=>'-32px 0'],
        ['en'=>'Ireland','no'=>'Irland','code'=>'353','pos'=>'-16px -539px'],
        // ['en'=>'Isle of Man','no'=>'','code'=>'44-1624','pos'=>'0 0'],
        ['en'=>'Israel','no'=>'Israel','code'=>'972','pos'=>'-16px -550px'],
        ['en'=>'Italy','no'=>'Italia','code'=>'39','pos'=>'-32px -33px'],
        ['en'=>'Ivory Coast','no'=>'Elfenbenskysten','code'=>'225','pos'=>'0 -462px'],
        ['en'=>'Jamaica','no'=>'Jamaica','code'=>'1-876','pos'=>'-32px -44px'],
        ['en'=>'Japan','no'=>'Japan','code'=>'81','pos'=>'-32px -66px'],
        // ['en'=>'Jersey','no'=>'','code'=>'44-1534','pos'=>''],
        ['en'=>'Jordan','no'=>'Jordan','code'=>'962','pos'=>'-32px -55px'],
        ['en'=>'Kazakhstan','no'=>'Kazakhstan','code'=>'7','pos'=>'-32px -187px'],
        ['en'=>'Kenya','no'=>'Kenya','code'=>'254','pos'=>'-32px -77px'],
        ['en'=>'Kiribati','no'=>'Kiribati','code'=>'686','pos'=>'-32px -110px'],
        // ['en'=>'Kosovo','no'=>'','code'=>'383','pos'=>'0 0'],
        ['en'=>'Kuwait','no'=>'Kuwait','code'=>'965','pos'=>'-32px -165px'],
        ['en'=>'Kyrgyzstan','no'=>'Kirghistan','code'=>'996','pos'=>'-32px -88px'],
        ['en'=>'Laos','no'=>'Laos','code'=>'856','pos'=>'-32px -198px'],
        ['en'=>'Latvia','no'=>'Latvia','code'=>'371','pos'=>'-32px -297px'],
        ['en'=>'Lebanon','no'=>'Libanon','code'=>'961','pos'=>'-32px -209px'],
        ['en'=>'Lesotho','no'=>'Lesotho','code'=>'266','pos'=>'-32px -264px'],
        ['en'=>'Liberia','no'=>'Liberia','code'=>'231','pos'=>'-32px -242px'],
        ['en'=>'Libya','no'=>'Libya','code'=>'218','pos'=>'-32px -308px'],
        ['en'=>'Liechtenstein','no'=>'Liechtenstein','code'=>'423','pos'=>'-32px -231px'],
        ['en'=>'Lithuania','no'=>'Litauen','code'=>'370','pos'=>'-32px -275px'],
        ['en'=>'Luxembourg','no'=>'Luxembourg','code'=>'352','pos'=>'-32px -286px'],
        ['en'=>'Macau','no'=>'Macau','code'=>'853','pos'=>'-32px -429px'],
        ['en'=>'North Macedonia','no'=>'Nord Makedonia','code'=>'389','pos'=>'-32px -385px'],
        ['en'=>'Madagascar','no'=>'Madagaskar','code'=>'261','pos'=>'-32px -363px'],
        ['en'=>'Malawi','no'=>'Malawi','code'=>'265','pos'=>'-32px -517px'],
        ['en'=>'Malaysia','no'=>'Malaysia','code'=>'60','pos'=>'-32px -539px'],
        ['en'=>'Maldives','no'=>'Maldivene','code'=>'960','pos'=>'-32px -506px'],
        ['en'=>'Mali','no'=>'Mali','code'=>'223','pos'=>'-32px -396px'],
        ['en'=>'Malta','no'=>'Malta','code'=>'356','pos'=>'-32px -484px'],
        ['en'=>'Marshall Islands','no'=>'Marshalløyene','code'=>'692','pos'=>'-32px -374px'],
        ['en'=>'Mauritania','no'=>'Mauritania','code'=>'222','pos'=>'-32px -462px'],
        ['en'=>'Mauritius','no'=>'Mauritius','code'=>'230','pos'=>'-32px -495px'],
        // ['en'=>'Mayotte','no'=>'','code'=>'262','pos'=>'-64px -341px'],
        ['en'=>'Mexico','no'=>'Mexico','code'=>'52','pos'=>'-32px -528px'],
        ['en'=>'Micronesia','no'=>'Mikronesia','code'=>'691','pos'=>'-16px -231px'],
        ['en'=>'Moldova','no'=>'Moldova','code'=>'373','pos'=>'-32px -341px'],
        ['en'=>'Monaco','no'=>'Monaco','code'=>'377','pos'=>'-32px -330px'],
        ['en'=>'Mongolia','no'=>'Mongolia','code'=>'976','pos'=>'-32px -418px'],
        ['en'=>'Montenegro','no'=>'Montenegro','code'=>'382','pos'=>'-32px -352px'],
        ['en'=>'Montserrat','no'=>'Montserrat','code'=>'1-664','pos'=>'-32px -473px'],
        ['en'=>'Morocco','no'=>'Marokko','code'=>'212','pos'=>'-32px -319px'],
        ['en'=>'Mozambique','no'=>'Mosambik','code'=>'258','pos'=>'-32px -550px'],
        ['en'=>'Myanmar','no'=>'Myanmar (Burma)','code'=>'95','pos'=>'-32px -407px'],
        ['en'=>'Namibia','no'=>'Namibia','code'=>'264','pos'=>'-32px -561px'],
        ['en'=>'Nauru','no'=>'Nauru','code'=>'674','pos'=>'-48px -77px'],
        ['en'=>'Nepal','no'=>'Nepal','code'=>'977','pos'=>'-48px -66px'],
        ['en'=>'Netherlands','no'=>'Holland','code'=>'31','pos'=>'-48px -44px'],
        ['en'=>'Netherlands Antilles','no'=>'De Nederlandske Antiller','code'=>'599','pos'=>'0 -77px'],
        ['en'=>'New Caledonia','no'=>'Ny Caledonia','code'=>'687','pos'=>'-32px -572px'],
        ['en'=>'New Zealand','no'=>'New Zealand','code'=>'64','pos'=>'-48px -99px'],
        ['en'=>'Nicaragua','no'=>'Nicaragua','code'=>'505','pos'=>'-48px -33px'],
        ['en'=>'Niger','no'=>'Niger','code'=>'227','pos'=>'-48px 0'],
        ['en'=>'Nigeria','no'=>'Nigeria','code'=>'234','pos'=>'-48px -22px'],
        ['en'=>'Niue','no'=>'Niue','code'=>'683','pos'=>'-48px -88px'],
        ['en'=>'North Korea','no'=>'Korea, Nord','code'=>'850','pos'=>'-32px -143px'],
        ['en'=>'Northern Mariana Islands','no'=>'Saipan','code'=>'1-670','pos'=>'-32px -440px'],
        ['en'=>'Norway','no'=>'Norge','code'=>'47','pos'=>'-48px -55px'],
        ['en'=>'Oman','no'=>'Oman','code'=>'968','pos'=>'-48px -110px'],
        ['en'=>'Pakistan','no'=>'Pakistan','code'=>'92','pos'=>'-48px -176px'],
        ['en'=>'Palau','no'=>'Palau','code'=>'680','pos'=>'-48px -253px'],
        ['en'=>'Palestine','no'=>'Palestina','code'=>'970','pos'=>'-48px -231px'],
        ['en'=>'Panama','no'=>'Panama','code'=>'507','pos'=>'-48px -121px'],
        ['en'=>'Papua New Guinea','no'=>'Papua - Ny Guinea','code'=>'675','pos'=>'-48px -154px'],
        ['en'=>'Paraguay','no'=>'Paraguay','code'=>'595','pos'=>'-48px -264px'],
        ['en'=>'Peru','no'=>'Peru','code'=>'51','pos'=>'-48px -132px'],
        ['en'=>'Philippines','no'=>'Filippinene','code'=>'63','pos'=>'-48px -165px'],
        // ['en'=>'Pitcairn','no'=>'','code'=>'64','pos'=>'-48px -209px'],
        ['en'=>'Poland','no'=>'Polen','code'=>'48','pos'=>'-48px -187px'],
        ['en'=>'Portugal','no'=>'Portugal','code'=>'351','pos'=>'-48px -242px'],
        ['en'=>'Puerto Rico','no'=>'Puerto Rico','code'=>'1-787, 1-939','pos'=>'-48px -220px'],
        ['en'=>'Qatar','no'=>'Quatar','code'=>'974','pos'=>'-48px -275px'],
        ['en'=>'Republic of the Congo','no'=>'Kongo','code'=>'242','pos'=>'0 -440px'],
        ['en'=>'Reunion','no'=>'Reunion','code'=>'262','pos'=>'-48px -286px'],
        ['en'=>'Romania','no'=>'Romania','code'=>'40','pos'=>'-48px -297px'],
        ['en'=>'Russia','no'=>'Russland','code'=>'7','pos'=>'-48px -319px'],
        ['en'=>'Rwanda','no'=>'Rwanda','code'=>'250','pos'=>'-48px -330px'],
        // ['en'=>'Saint Barthelemy','no'=>'','code'=>'590','pos'=>'0 0'],
        ['en'=>'Saint Helena','no'=>'St. Helena','code'=>'290','pos'=>'-48px -418px'],
        ['en'=>'Saint Kitts and Nevis','no'=>'St. Kitts and Nevis (Leeward-øyene)','code'=>'1-869','pos'=>'-32px -132px'],
        ['en'=>'Saint Lucia','no'=>'St. Lucia (Winward-øyene)','code'=>'1-758','pos'=>'-32px -220px'],
        // ['en'=>'Saint Martin','no'=>'','code'=>'590','pos'=>'0 0'],
        ['en'=>'Saint Pierre and Miquelon','no'=>'St. Pierre and Miquelon','code'=>'508','pos'=>'-48px -198px'],
        ['en'=>'Saint Vincent and the Grenadines','no'=>'Saint Vincent og Grenadinene','code'=>'1-784','pos'=>'-64px -231px'],
        ['en'=>'Samoa','no'=>'Samoa - West','code'=>'685','pos'=>'-64px -319px'],
        ['en'=>'San Marino','no'=>'San Marino','code'=>'378','pos'=>'-48px -473px'],
        ['en'=>'Sao Tome and Principe','no'=>'Sao Tome & Principe','code'=>'239','pos'=>'-48px -517px'],
        ['en'=>'Saudi Arabia','no'=>'Saudi Arabia','code'=>'966','pos'=>'-48px -341px'],
        ['en'=>'Senegal','no'=>'Senegal','code'=>'221','pos'=>'-48px -484px'],
        ['en'=>'Serbia','no'=>'Serbia og Montenegro','code'=>'381','pos'=>'-48px -308px'],
        ['en'=>'Seychelles','no'=>'Seychellene','code'=>'248','pos'=>'-48px -363px'],
        ['en'=>'Sierra Leone','no'=>'Sierra Leone','code'=>'232','pos'=>'-48px -462px'],
        ['en'=>'Singapore','no'=>'Singapore','code'=>'65','pos'=>'-48px -407px'],
        // ['en'=>'Sint Maarten','no'=>'','code'=>'1-721','pos'=>'0 0'],
        ['en'=>'Slovakia','no'=>'Slovakia','code'=>'421','pos'=>'-48px -451px'],
        ['en'=>'Slovenia','no'=>'Slovenia','code'=>'386','pos'=>'-48px -429px'],
        ['en'=>'Solomon Islands','no'=>'Salomon-øyene','code'=>'677','pos'=>'-48px -352px'],
        ['en'=>'Somalia','no'=>'Somalia','code'=>'252','pos'=>'-48px -495px'],
        ['en'=>'South Africa','no'=>'Sør-Afrika Republikken','code'=>'27','pos'=>'-64px -352px'],
        ['en'=>'South Korea','no'=>'Korea, Sør','code'=>'82','pos'=>'-32px -154px'],
        // ['en'=>'South Sudan','no'=>'','code'=>'211','pos'=>'0 0'],
        ['en'=>'Spain','no'=>'Spania','code'=>'34','pos'=>'-16px -154px'],
        ['en'=>'Sri Lanka','no'=>'Sri Lanka','code'=>'94','pos'=>'-32px -253px'],
        ['en'=>'Sudan','no'=>'Sudan','code'=>'249','pos'=>'-48px -385px'],
        ['en'=>'Suriname','no'=>'Surinam','code'=>'597','pos'=>'-48px -506px'],
        // ['en'=>'Svalbard and Jan Mayen','no'=>'','code'=>'47','pos'=>'-48px -440px'],
        ['en'=>'Swaziland','no'=>'Swaziland','code'=>'268','pos'=>'-48px -550px'],
        ['en'=>'Sweden','no'=>'Sverige','code'=>'46','pos'=>'-48px -396px'],
        ['en'=>'Switzerland','no'=>'Sveits','code'=>'41','pos'=>'0 -451px'],
        ['en'=>'Syria','no'=>'Syria','code'=>'963','pos'=>'-48px -539px'],
        ['en'=>'Taiwan','no'=>'Taiwan','code'=>'886','pos'=>'-64px -132px'],
        ['en'=>'Tajikistan','no'=>'Tadzjikistan','code'=>'992','pos'=>'-64px -33px'],
        ['en'=>'Tanzania','no'=>'Tanzania','code'=>'255','pos'=>'-64px -143px'],
        ['en'=>'Thailand','no'=>'Thailand','code'=>'66','pos'=>'-64px -22px'],
        ['en'=>'Togo','no'=>'Togo','code'=>'228','pos'=>'-64px -11px'],
        ['en'=>'Tokelau','no'=>'Tokelau','code'=>'690','pos'=>'-64px -44px'],
        ['en'=>'Tonga','no'=>'Tonga','code'=>'676','pos'=>'-64px -88px'],
        ['en'=>'Trinidad and Tobago','no'=>'Trinidad and Tobago','code'=>'1-868','pos'=>'-64px -110px'],
        ['en'=>'Tunisia','no'=>'Tunisia','code'=>'216','pos'=>'-64px -77px'],
        ['en'=>'Turkey','no'=>'Tyrkia','code'=>'90','pos'=>'-64px -99px'],
        ['en'=>'Turkmenistan','no'=>'Turkmenistan','code'=>'993','pos'=>'-64px -66px'],
        ['en'=>'Turks and Caicos Islands','no'=>'Turcs- and Caicos-øyene','code'=>'1-649','pos'=>'-48px -561px'],
        ['en'=>'Tuvalu','no'=>'Tuvalu','code'=>'688','pos'=>'-64px -121px'],
        ['en'=>'U.S. Virgin Islands','no'=>'Jomfruøyene - US','code'=>'1-340','pos'=>'-64px -264px'],
        ['en'=>'Uganda','no'=>'Uganda','code'=>'256','pos'=>'-64px -165px'],
        ['en'=>'Ukraine','no'=>'Ukraina','code'=>'380','pos'=>'-64px -154px'],
        ['en'=>'United Arab Emirates','no'=>'De Forente Arabiske Emirater','code'=>'971','pos'=>'0 -11px'],
        ['en'=>'United Kingdom','no'=>'England','code'=>'44','pos'=>'-16px -275px'],
        ['en'=>'United States','no'=>'USA','code'=>'1','pos'=>'-64px -187px'],
        ['en'=>'Uruguay','no'=>'Uruguay','code'=>'598','pos'=>'-64px -198px'],
        ['en'=>'Uzbekistan','no'=>'Uzbekistan','code'=>'998','pos'=>'-64px -209px'],
        ['en'=>'Vanuatu','no'=>'Vanuatu','code'=>'678','pos'=>'-64px -286px'],
        ['en'=>'Vatican','no'=>'Vatikanet','code'=>'379','pos'=>'-64px -220px'],
        ['en'=>'Venezuela','no'=>'Venezuela','code'=>'58','pos'=>'-64px -242px'],
        ['en'=>'Vietnam','no'=>'Vietnam','code'=>'84','pos'=>'-64px -275px'],
        // ['en'=>'Wallis and Futuna','no'=>'Wallis og Futuna','code'=>'681','pos'=>'-64px -308px'],
        // ['en'=>'Western Sahara','no'=>'Marokko','code'=>'212','pos'=>'-16px -121px'],
        ['en'=>'Yemen','no'=>'Yemen','code'=>'967','pos'=>'-64px -330px'],
        ['en'=>'Zambia','no'=>'Zambia','code'=>'260','pos'=>'-64px -363px'],
        ['en'=>'Zimbabwe','no'=>'Zimbabwe','code'=>'263','pos'=>'-64px -374px']
    ];*/

    function __construct()
    {
    	// db
    	$this->db = DataBase::getDB();

    	// lang
    	$this->lang = Language::getLang();
        // var_dump($this->lang);

        /*$sql = "TRUNCATE TABLE `countries`";
        $this->db->query($sql);

        $sql = "TRUNCATE TABLE `countries_description`";
        $this->db->query($sql);

        foreach ($this->countries as $country) {
            $sql = "INSERT INTO `countries` SET `code` = {?}, `pos` = {?}";
            $country_id = $this->db->query($sql, [$country['code'], $country['pos']]);
            if ($country_id) {
                $sql = "INSERT INTO `countries_description` SET `country_id` = {?}, `lang_id` = {?}, `name` = {?}";
                $this->db->query($sql, [$country_id, 3, $country['en']]);

                $sql = "INSERT INTO `countries_description` SET `country_id` = {?}, `lang_id` = {?}, `name` = {?}";
                $this->db->query($sql, [$country_id, 4, $country['no']]);
            }
        }
        exit();*/

    	// user
    	$this->user = User::getUser();
    	$this->userInfo = $this->user->isAutorized();

    	// access pages
    	if ($this->userInfo) {
            $query = "SELECT `id`, `method`, `menu_lang_field`, `menu_lang_field_child`, `menu_icon`, `parent_id`, `url` FROM `url_alias_admin` WHERE `status` = 1 ORDER BY `sort`";
	    	$admin_urls = $this->db->select($query);
	        if ($admin_urls && count($admin_urls) > 0) {
	            foreach ($admin_urls as $admin_url) {
	                $query = "SELECT `access_view`, `access_edit` FROM `url_alias_admin_access_role` WHERE `url_alias_id` = {?} AND `role_id` = {?} LIMIT 1";
	                $access_data = $this->db->selectRow($query, [$admin_url['id'], $this->userInfo['role_id']]);
	                if ($access_data) {
	                	$this->accessPage[$admin_url['method']] = [
	                		'access_view' => $access_data['access_view'],
	                		'access_edit' => $access_data['access_edit'],
	                		'menu_lang_field' => (!empty($admin_url['menu_lang_field']) ? $this->lang->getWordsParam($admin_url['menu_lang_field']) : 'No text'),
	                		'menu_lang_field_child' => (!empty($admin_url['menu_lang_field_child']) ? $this->lang->getWordsParam($admin_url['menu_lang_field_child']) : 'No text'),
	                		'menu_icon' => $admin_url['menu_icon'],
	                		'parent_id' => $admin_url['parent_id'],
	                		'url' => $admin_url['url']
	                	];
	                }
	            }
	        }
	    }

	    // link without page param
	    $url_param = $this->getUrlPag();

	    $this->urlPag = '/' . $url_param[0];
	    if (count($url_param[1]) > 0) {
	    	$this->urlPag .= '?';

	    	foreach ($url_param[1] as $get_field => $get_val) {
	    		if ($get_field == 'page') {
	    			continue;
	    		}

	    		$this->urlPag .= $get_field . '=' . $get_val . '&';
	    	}

	    	$this->urlPag = substr($this->urlPag, 0, -1);
	    }

	    // page - get param for pagination
	    $this->page = (isset($_GET['page'])) ? (int) $_GET['page'] : 1;

	    // settings
	    $query = "SELECT `field_variable`, `value` FROM `settings` ORDER BY `id`";
	    $setting_db = $this->db->select($query);
	    if ($setting_db) {
	    	foreach ($setting_db as $setting_item) {
	    		$this->settings[$setting_item['field_variable']] = $setting_item['value'];
	    	}
	    }

	    // start pagination
	    $this->start = ($this->page - 1) * $this->settings['limit'];

        $this->lang_id = $this->lang->getDefaultLanguageId();

        // $this->orderModelObject = new OrderModel();

        // проверяем, есть ли доступ к этой странице
        if ($this->urlPag[0] == '/') {
            $check_url = substr($this->urlPag, 1);
        } else {
            $check_url = $this->urlPag;
        }

        if (stripos($check_url, '?') !== false) {
            $parts = explode('?', $check_url);
            $check_url = $parts[0];
        }

        $query = "SELECT `method` FROM `url_alias_admin` WHERE `url` = {?} LIMIT 1";
        $check_method = $this->db->selectCell($query, [$check_url]);
        if ((!$check_method || !$this->ifAccessPage($check_method)) && $this->userInfo) {
            $this->notFound();
        }

        // function
        $this->function = Functions::getFunctions();
    }

    // чи є доступ до сторінки
    private function ifAccessPage($section)
    {
    	$return = false;

    	if (array_key_exists($section, $this->accessPage)) {
    		if (!empty($this->accessPage[$section]['access_view'])) {
    			$return = true;
    		}
    	}

    	return $return;
    }

    // конвертуємо дату з російського формату у англійський (Y-m-d)
    protected function fromRusDatetimeToEng($datetime)
    {
        $return = '';

        if (stripos($datetime, ' ') !== false) {
            $parts = explode(' ', $datetime);

            $from_date = $parts[0];
            $from_time = $parts[1];
        } else {
            $from_date = $datetime;
            $from_time = '';
        }

        if (stripos($from_date, '.') !== false) {
            $parts1 = explode('.', $from_date);
            for ($i = count($parts1) - 1; $i >= 0; $i--) { 
                $return .= $parts1[$i] . '-';
            }
            $return = substr($return, 0, -1);
        }

        if (!empty($from_time)) {
            $time_object = new DateTime($from_time);
            $return .= ' ' . $time_object->format('H:i');
        }

        return $return;
    }

    // конвертуємо дату з англійського формату у російський (d.m.Y)
    protected function fromEngDatetimeToRus($datetime)
    {
        $return = '';

        if (stripos($datetime, ' ') !== false) {
            $parts = explode(' ', $datetime);

            $from_date = $parts[0];
            $from_time = $parts[1];
        } else {
            $from_date = $datetime;
            $from_time = '';
        }

        if (stripos($from_date, '-') !== false) {
            $parts1 = explode('-', $from_date);
            for ($i = count($parts1) - 1; $i >= 0; $i--) { 
                $return .= $parts1[$i] . '.';
            }
            $return = substr($return, 0, -1);
        }

        if (!empty($from_time)) {
            $time_object = new DateTime($from_time);
            $return .= ' ' . $time_object->format('H:i');
        }

        return $return;
    }

    // ip юзера
    protected function getIp() {
        $ipaddress = '';

        if (getenv('HTTP_CLIENT_IP')) {
            $ipaddress = getenv('HTTP_CLIENT_IP');
        } else if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        } else if (getenv('HTTP_X_FORWARDED')) {
            $ipaddress = getenv('HTTP_X_FORWARDED');
        } else if (getenv('HTTP_FORWARDED_FOR')) {
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        } else if (getenv('HTTP_FORWARDED')) {
            $ipaddress = getenv('HTTP_FORWARDED');
        } else if (getenv('REMOTE_ADDR')) {
            $ipaddress = getenv('REMOTE_ADDR');
        } else {
            $ipaddress = 'UNKNOWN';
        }

        return $ipaddress;
    }

    // обробка урла для формування пагінації
    private function getUrlPag()
    {
        $getParams = array();
        $url = trim($_SERVER['REQUEST_URI']);
        if (stristr($url, '?') !== false) {
            $parts = explode("?", $url);
            $url = preg_replace("/\/+/", '/', $parts[0]);

            if (stristr($parts[1], '&') !== false) {
                $parts1 = explode("&", $parts[1]);

                foreach ($parts1 as $value) {
                    if (stristr($value, '=') !== false) {
                        $parts2 = explode("=", $value);
                        $key = $parts2[0];
                        $val = $parts2[1];
                        $getParams[$key] = $val;
                    }
                }
            } else {
                if (stristr($parts[1], '=') !== false) {
                    $parts1 = explode("=", $parts[1]);
                    $key = $parts1[0];
                    $val = $parts1[1];
                    $getParams[$key] = $val;
                } else {
                    $key = $parts[1];
                    $getParams[$key] = '';
                }
            }
        } else {
            $url = preg_replace("/\/+/", '/', $url);
        }

        $url = preg_replace("/^\/(.*)\/?$/U", '\\1', $url);

        return array($url, $getParams);
    }

    protected function startPagination($qt)
    {
        $this->pagination = new Pagination();
        $this->pagination->total = $qt;
        $this->pagination->page = $this->page;
        $this->pagination->limit = $this->settings['limit'];
        $this->pagination->url = $this->urlPag . (stripos($this->urlPag, '?') !== false ? '&' : '?') . 'page={page}';
    }

    // создание папки
    protected function createFolder($createPath)
    {
        $path = '';
        $directories = explode('/', $createPath);
        foreach ($directories as $directory) {
            $path = $path . '/' . $directory;

            if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $path)) {
                @mkdir($_SERVER['DOCUMENT_ROOT'] . $path, 0777);
            }
        }
    }





    // головна сторінка адмінки
    public function index()
    {
        if ($this->userInfo) {
            $query = "SELECT `url_alias_admin_first_id` FROM `users_role` WHERE `id` = {?} LIMIT 1";
            $url_alias_admin_first_id = $this->db->selectCell($query, [(int) $this->userInfo['role_id']]);
            if ($url_alias_admin_first_id) {
                $query = "SELECT `url` FROM `url_alias_admin` WHERE `id` = {?} LIMIT 1";
                $url_alias_admin = $this->db->selectCell($query, [$url_alias_admin_first_id]);
                if ($url_alias_admin) {
                    header('Location: /' . $url_alias_admin);
                    exit();
                } else {
                    require_once(ROOT . '/view/template/main.php');
                }
            } else {
                require_once(ROOT . '/view/template/main.php');
            }
        } else {
            require_once(ROOT . '/view/template/main.php');
        }
    }

    public function notFound($message = '')
    {
        /*$this->body_class[] = 'd-flex';
        $this->body_class[] = 'align-items-center';
        $this->body_class[] = 'bg-auth';
        $this->body_class[] = 'border-top';
        $this->body_class[] = 'border-top-2';
        $this->body_class[] = 'border-primary';*/

        $this->body_class[] = 'wf_body_404';

        if (empty($message)) {
            $message = 'Looks like you ended up here by accident?';
        }

        require_once(ROOT . '/view/template/404.php');
        exit();
    }














    /*public function refresh()
    {
        $sql = "TRUNCATE TABLE `chat_messages`";
        $this->db->query($sql);

        // $sql = "TRUNCATE TABLE `in`";
        // $this->db->query($sql);

        $sql = "TRUNCATE TABLE `teams`";
        $this->db->query($sql);

        $sql = "
            INSERT INTO `teams`
            SET `team_name` = {?},
                `code` = {?},
                `create` = NOW(),
                `score` = {?},
                `progress_percent` = {?},
                `dashboard` = {?},
                `timer_second` = {?},
                `active_hints` = {?},
                `list_hints` = {?},
                `list_hints_title_lang_var` = {?},
                `list_hints_text_lang_var` = {?},
                `active_files` = {?},
                `list_files` = {?},
                `active_databases` = {?},
                `list_databases` = {?},
                `last_action_id` = {?},
                `calls_outgoing_id` = {?},
                `active_calls` = {?},
                `car_register_country_id` = {?},
                `car_register_date` = {?},
                `car_register_print_text_huilov` = {?},
                `private_individuals_print_text_huilov` = {?},
                `ceo_database_print_text_rod` = {?},
                `mobile_calls_country_id` = {?},
                `mobile_calls_number` = {?},
                `mobile_calls_print_messages` = {?}
        ";
        $this->db->query($sql, ['', 'test', 0, 0, 'dashboard', 0, json_encode([], JSON_UNESCAPED_UNICODE), json_encode([1,2,3], JSON_UNESCAPED_UNICODE), 'text26', 'text27', json_encode([], JSON_UNESCAPED_UNICODE), json_encode([1], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), json_encode([], JSON_UNESCAPED_UNICODE), 2, 2, json_encode([['id'=>1,'datetime'=>'']], JSON_UNESCAPED_UNICODE), 0, NULL, 0, 0, 0, 0, NULL, 0]);

        $sql = "TRUNCATE TABLE `team_history_action`";
        $this->db->query($sql);

        $sql = "TRUNCATE TABLE `users`";
        $this->db->query($sql);
    }*/
}
