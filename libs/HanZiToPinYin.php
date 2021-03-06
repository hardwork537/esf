<?php

/**
 * @abstract 汉字转换拼音操作类
 * @author tonyzhao <tonyzhao@sohu-inc.com>
 * @date 2014-09-03
 */
class HanZiToPinYin {

    //拼音字符库
    protected $pinyin_config = array(
        array("a", -20319),
        array("ai", -20317),
        array("an", -20304),
        array("ang", -20295),
        array("ao", -20292),
        array("ba", -20283),
        array("bai", -20265),
        array("ban", -20257),
        array("bang", -20242),
        array("bao", -20230),
        array("bei", -20051),
        array("ben", -20036),
        array("beng", -20032),
        array("bi", -20026),
        array("bian", -20002),
        array("biao", -19990),
        array("bie", -19986),
        array("bin", -19982),
        array("bing", -19976),
        array("bo", -19805),
        array("bu", -19784),
        array("ca", -19775),
        array("cai", -19774),
        array("can", -19763),
        array("cang", -19756),
        array("cao", -19751),
        array("ce", -19746),
        array("ceng", -19741),
        array("cha", -19739),
        array("chai", -19728),
        array("chan", -19725),
        array("chang", -19715),
        array("chao", -19540),
        array("che", -19531),
        array("chen", -19525),
        array("cheng", -19515),
        array("chi", -19500),
        array("chong", -19484),
        array("chou", -19479),
        array("chu", -19467),
        array("chuai", -19289),
        array("chuan", -19288),
        array("chuang", -19281),
        array("chui", -19275),
        array("chun", -19270),
        array("chuo", -19263),
        array("ci", -19261),
        array("cong", -19249),
        array("cou", -19243),
        array("cu", -19242),
        array("cuan", -19238),
        array("cui", -19235),
        array("cun", -19227),
        array("cuo", -19224),
        array("da", -19218),
        array("dai", -19212),
        array("dan", -19038),
        array("dang", -19023),
        array("dao", -19018),
        array("de", -19006),
        array("deng", -19003),
        array("di", -18996),
        array("dian", -18977),
        array("diao", -18961),
        array("die", -18952),
        array("ding", -18783),
        array("diu", -18774),
        array("dong", -18773),
        array("dou", -18763),
        array("du", -18756),
        array("duan", -18741),
        array("dui", -18735),
        array("dun", -18731),
        array("duo", -18722),
        array("e", -18710),
        array("en", -18697),
        array("er", -18696),
        array("fa", -18526),
        array("fan", -18518),
        array("fang", -18501),
        array("fei", -18490),
        array("fen", -18478),
        array("feng", -18463),
        array("fo", -18448),
        array("fou", -18447),
        array("fu", -18446),
        array("ga", -18239),
        array("gai", -18237),
        array("gan", -18231),
        array("gang", -18220),
        array("gao", -18211),
        array("ge", -18201),
        array("gei", -18184),
        array("gen", -18183),
        array("geng", -18181),
        array("gong", -18012),
        array("gou", -17997),
        array("gu", -17988),
        array("gua", -17970),
        array("guai", -17964),
        array("guan", -17961),
        array("guang", -17950),
        array("gui", -17947),
        array("gun", -17931),
        array("guo", -17928),
        array("ha", -17922),
        array("hai", -17759),
        array("han", -17752),
        array("hang", -17733),
        array("hao", -17730),
        array("he", -17721),
        array("hei", -17703),
        array("hen", -17701),
        array("heng", -17697),
        array("hong", -17692),
        array("hou", -17683),
        array("hu", -17676),
        array("hua", -17496),
        array("huai", -17487),
        array("huan", -17482),
        array("huang", -17468),
        array("hui", -17454),
        array("hun", -17433),
        array("huo", -17427),
        array("ji", -17417),
        array("jia", -17202),
        array("jian", -17185),
        array("jiang", -16983),
        array("jiao", -16970),
        array("jie", -16942),
        array("jin", -16915),
        array("jing", -16733),
        array("jiong", -16708),
        array("jiu", -16706),
        array("ju", -16689),
        array("juan", -16664),
        array("jue", -16657),
        array("jun", -16647),
        array("ka", -16474),
        array("kai", -16470),
        array("kan", -16465),
        array("kang", -16459),
        array("kao", -16452),
        array("ke", -16448),
        array("ken", -16433),
        array("keng", -16429),
        array("kong", -16427),
        array("kou", -16423),
        array("ku", -16419),
        array("kua", -16412),
        array("kuai", -16407),
        array("kuan", -16403),
        array("kuang", -16401),
        array("kui", -16393),
        array("kun", -16220),
        array("kuo", -16216),
        array("la", -16212),
        array("lai", -16205),
        array("lan", -16202),
        array("lang", -16187),
        array("lao", -16180),
        array("le", -16171),
        array("lei", -16169),
        array("leng", -16158),
        array("li", -16155),
        array("lia", -15959),
        array("lian", -15958),
        array("liang", -15944),
        array("liao", -15933),
        array("lie", -15920),
        array("lin", -15915),
        array("ling", -15903),
        array("liu", -15889),
        array("long", -15878),
        array("lou", -15707),
        array("lu", -15701),
        array("lv", -15681),
        array("luan", -15667),
        array("lue", -15661),
        array("lun", -15659),
        array("luo", -15652),
        array("ma", -15640),
        array("mai", -15631),
        array("man", -15625),
        array("mang", -15454),
        array("mao", -15448),
        array("me", -15436),
        array("mei", -15435),
        array("men", -15419),
        array("meng", -15416),
        array("mi", -15408),
        array("mian", -15394),
        array("miao", -15385),
        array("mie", -15377),
        array("min", -15375),
        array("ming", -15369),
        array("miu", -15363),
        array("mo", -15362),
        array("mou", -15183),
        array("mu", -15180),
        array("na", -15165),
        array("nai", -15158),
        array("nan", -15153),
        array("nang", -15150),
        array("nao", -15149),
        array("ne", -15144),
        array("nei", -15143),
        array("nen", -15141),
        array("neng", -15140),
        array("ni", -15139),
        array("nian", -15128),
        array("niang", -15121),
        array("niao", -15119),
        array("nie", -15117),
        array("nin", -15110),
        array("ning", -15109),
        array("niu", -14941),
        array("nong", -14937),
        array("nu", -14933),
        array("nv", -14930),
        array("nuan", -14929),
        array("nue", -14928),
        array("nuo", -14926),
        array("o", -14922),
        array("ou", -14921),
        array("pa", -14914),
        array("pai", -14908),
        array("pan", -14902),
        array("pang", -14894),
        array("pao", -14889),
        array("pei", -14882),
        array("pen", -14873),
        array("peng", -14871),
        array("pi", -14857),
        array("pian", -14678),
        array("piao", -14674),
        array("pie", -14670),
        array("pin", -14668),
        array("ping", -14663),
        array("po", -14654),
        array("pu", -14645),
        array("qi", -14630),
        array("qia", -14594),
        array("qian", -14429),
        array("qiang", -14407),
        array("qiao", -14399),
        array("qie", -14384),
        array("qin", -14379),
        array("qing", -14368),
        array("qiong", -14355),
        array("qiu", -14353),
        array("qu", -14345),
        array("quan", -14170),
        array("que", -14159),
        array("qun", -14151),
        array("ran", -14149),
        array("rang", -14145),
        array("rao", -14140),
        array("re", -14137),
        array("ren", -14135),
        array("reng", -14125),
        array("ri", -14123),
        array("rong", -14122),
        array("rou", -14112),
        array("ru", -14109),
        array("ruan", -14099),
        array("rui", -14097),
        array("run", -14094),
        array("ruo", -14092),
        array("sa", -14090),
        array("sai", -14087),
        array("san", -14083),
        array("sang", -13917),
        array("sao", -13914),
        array("se", -13910),
        array("sen", -13907),
        array("seng", -13906),
        array("sha", -13905),
        array("shai", -13896),
        array("shan", -13894),
        array("shang", -13878),
        array("shao", -13870),
        array("she", -13859),
        array("shen", -13847),
        array("sheng", -13831),
        array("shi", -13658),
        array("shou", -13611),
        array("shu", -13601),
        array("shua", -13406),
        array("shuai", -13404),
        array("shuan", -13400),
        array("shuang", -13398),
        array("shui", -13395),
        array("shun", -13391),
        array("shuo", -13387),
        array("si", -13383),
        array("song", -13367),
        array("sou", -13359),
        array("su", -13356),
        array("suan", -13343),
        array("sui", -13340),
        array("sun", -13329),
        array("suo", -13326),
        array("ta", -13318),
        array("tai", -13147),
        array("tan", -13138),
        array("tang", -13120),
        array("tao", -13107),
        array("te", -13096),
        array("teng", -13095),
        array("ti", -13091),
        array("tian", -13076),
        array("tiao", -13068),
        array("tie", -13063),
        array("ting", -13060),
        array("tong", -12888),
        array("tou", -12875),
        array("tu", -12871),
        array("tuan", -12860),
        array("tui", -12858),
        array("tun", -12852),
        array("tuo", -12849),
        array("wa", -12838),
        array("wai", -12831),
        array("wan", -12829),
        array("wang", -12812),
        array("wei", -12802),
        array("wen", -12607),
        array("weng", -12597),
        array("wo", -12594),
        array("wu", -12585),
        array("xi", -12556),
        array("xia", -12359),
        array("xian", -12346),
        array("xiang", -12320),
        array("xiao", -12300),
        array("xie", -12120),
        array("xin", -12099),
        array("xing", -12089),
        array("xiong", -12074),
        array("xiu", -12067),
        array("xu", -12058),
        array("xuan", -12039),
        array("xue", -11867),
        array("xun", -11861),
        array("ya", -11847),
        array("yan", -11831),
        array("yang", -11798),
        array("yao", -11781),
        array("ye", -11604),
        array("yi", -11589),
        array("yin", -11536),
        array("ying", -11358),
        array("yo", -11340),
        array("yong", -11339),
        array("you", -11324),
        array("yu", -11303),
        array("yuan", -11097),
        array("yue", -11077),
        array("yun", -11067),
        array("za", -11055),
        array("zai", -11052),
        array("zan", -11045),
        array("zang", -11041),
        array("zao", -11038),
        array("ze", -11024),
        array("zei", -11020),
        array("zen", -11019),
        array("zeng", -11018),
        array("zha", -11014),
        array("zhai", -10838),
        array("zhan", -10832),
        array("zhang", -10815),
        array("zhao", -10800),
        array("zhe", -10790),
        array("zhen", -10780),
        array("zheng", -10764),
        array("zhi", -10587),
        array("zhong", -10544),
        array("zhou", -10533),
        array("zhu", -10519),
        array("zhua", -10331),
        array("zhuai", -10329),
        array("zhuan", -10328),
        array("zhuang", -10322),
        array("zhui", -10315),
        array("zhun", -10309),
        array("zhuo", -10307),
        array("zi", -10296),
        array("zong", -10281),
        array("zou", -10274),
        array("zu", -10270),
        array("zuan", -10262),
        array("zui", -10260),
        array("zun", -10256),
        array("zuo", -10254)
    );
    //特殊字符库
    private $__special_config = array(
        array("-12349", "-13905"),
        array('-2354', '-12099'),
        array('-7431', '-11589'),
        array('-6712', '-17202'),
        array('-9040', '-12099'),
        array('-2069', '-15915'),
        array('-9262', '-18526'),
        array('-5697', '-12120'),
        array('-5427', '-17454'),
        array('-3931', '-18446'),
        array('-9311', '-18996'),
        array('-6169', '-15878'),
        array('-5671', '-16689'),
        array('-6729', '-19525'),
        array('-6743', '-17730'),
        array('-5431', '-13831'),
        array('-6932', '-12300'),
        array('-5691', '-14122'),
        array('-8985', '-14429'),
        array('-5416', '-12556'),
        array('-9018', '-10587'),
        array('-5707', '-15681'),
        array('-6171', '-16657'),
        array('-2560', '-13658'),
        array('-21158', '-16733'),
        array('-8968', '-15369'),
        array('-4939', '-16202'),
        array('-5692', '-19982'),
        array('-6924', '-11589'),
        array('-4437', '-16465'),
        array('-5937', '-10790'),
        array('-6739', '-11358'),
        array('-5445', '-17730'),
        array('-9027', '-18446'),
        array('-2844', '-18490'),
        array('-6422', '-16155'),
        array('-9004', '-15362'),
        array('-4690', '-15448'),
        array('-5455', '-14921'),
        array('-4873', '-14630'),
        array('-6722', '-17482'),
        array('-8532', '-18518'),
        array('-3121', '-13831'),
        array('-5423', '-12039'),
        array('-4921', '-11604'),
        array('-9254', '-10780'),
        array('-5128', '-13095'),
        array('-2072', '-14630'),
        array('-6153', '-14630'),
        array('-7760', '-16202'),
        array('-6149', '-16220'),
        array('-9025', '-11067'),
        array('-5897', '-10296'),
        array('-5672', '-12556'),
        array('-3133', '-10519'),
        array('-4923', '-12039'),
        array('-6156', '-12556'),
        array('-5715', '-16202'),
        array('-8970', '-17454'),
        array('-8734', '-14914'),
        array('-8527', '-12802'),
        array('-2877', '-14353'),
        array('-6428', '-11589'),
        array('-9302', '-16155'),
        array('-10045', '-19805'),
        array('-2642', '-13091'),
        array('-6951', '-11831'),
        array('-9038', '-11589'),
        array('-4869', '-12556'),
        array('-5963', '-20026'),
        array('-3927', '-17730'),
        array('-7466', '-20304'),
        array('-3074', '-17947'),
        array('-6180', '-17417'),
        array('-2908', '-19805'),
        array('-7223', '-15375'),
        array('-5909', '-17496'),
        array('-8516', '-16155'),
        array('-6217', '-13611'),
        array('-6222', '-14630'),
        array('-4652', '-18735'),
        array('-6741', '-17752'),
        array('-3421', '-11358'),
        array('-6493', '-17947'),
        array('-2389', '-17417'),
        array('-6976', '-12556'),
        array('-9788', '-17961'),
        array('-7206', '-14159'),
        array('-4647', '-16942'),
        array('-2907', '-16205'),
        array('-2321', '-16220'),
        array('-3906', '-19805'),
        array('-9798', '-18463'),
        array('-21386', '-13091'),
        array('-7172', '-17692'),
        array('-4426', '-12860'),
        array('-2361', '-15667'),
        array('-6193', '-15625'),
        array('-9823', '-12888'),
        array('-3652', '-18763'),
        array('-9241', '-12849'),
        array('-1662', '-16733'),
        array('-8755', '-19235'),
        array('-5635', '-15128'),
        array('-6973', '-10307'),
        array('-4445', '-14097'),
        array('-5690', '-10519'),
        array('-7735', '-14122'),
        array('-8730', '-12039'),
        array('-2143', '-20292'),
        array('-2394', '-15944'),
        array('-6916', '-12888'),
        array('-5901', '-18518'),
        array('-4872', '-19725'),
        array('-6166', '-14654'),
        array('-6150', '-17676'),
        array('-5673', '-10256'),
        array('-9764', '-13859'),
        array('-6174', '-12802'),
        array('-4398', '-16689'),
        array('-7170', '-16733'),
        array('-6930', '-19751'),
        array('-7180', '-13383'),
        array('-6975', '-13367'),
        array('-7518', '-11589'),
        array('-8777', '-13847'),
        array('-6210', '-13091'),
        array('-7235', '-16733'),
        array('-7733', '-12802'),
        array('-5966', '-19763'),
        array('-7194', '-15394'),
        array('-3417', '-10328'),
        array('-7740', '-17988'),
        array('-5718', '-15153'),
        array('-6979', '-17482'),
        array('-8540', '-15933'),
        array('-2104', '-15435'),
        array('-7427', '-16470'),
        array('-6736', '-17730'),
        array('-7189', '-15419'),
        array('-6170', '-16448'),
        array('-9486', '-17468'),
        array('-5977', '-15149'),
        array('-9805', '-16155'),
        array('-8486', '-11589'),
        array('-8772', '-16733'),
        array('-5967', '-14645'),
        array('-6982', '-20242'),
        array('-2893', '-18501'),
        array('-4881', '-17676'),
        array('-8745', '-18463'),
        array('-8476', '-16689'),
        array('-2356', '-15889'),
        array('-7744', '-16180'),
        array('-8297', '-19227'),
        array('-4424', '-18220'),
        array('-9567', '-13367'),
        array('-7753', '-19212'),
        array('-6968', '-19249'),
        array('-9242', '-18996'),
        array('-2849', '-17417'),
        array('-3408', '-14353'),
        array('-6151', '-14630'),
        array('-7766', '-14630'),
        array('-6992', '-17676'),
        array('-6662', '-18490'),
        array('-6438', '-18446'),
        array('-4876', '-19261'),
        array('-5441', '-12099'),
        array('-8539', '-17454'),
        array('-6944', '-16155'),
        array('-7953', '-15153'),
        array('-20836', '-15889'),
        array('-3630', '-19467'),
        array('-6165', '-15375'),
        array('-6421', '-14630'),
        array('-3118', '-16155'),
        array('-2892', '-18201'),
        array('-6176', '-18783'),
        array('-5706', '-19739'),
        array('-9479', '-12585'),
        array('-5974', '-16915'),
        array('-3922', '-11339'),
        array('-6993', '-15889'),
        array('-7197', '-18463'),
        array('-9495', '-20051'),
        array('-5969', '-12039'),
        array('-7183', '-15878'),
        array('-5434', '-19715'),
        array('-2311', '-18710'),
        array('-5428', '-11831'),
        array('-7710', '-16205'),
        array('-9520', '-18996'),
        array('-4434', '-13060'),
        array('-6195', '-19982'),
        array('-6745', '-14645'),
        array('-9248', '-20257'),
        array('-21656', '-11077'),
        array('-3148', '-14902'),
        array('-9820', '-17202'),
        array('-10028', '-17970'),
        array('-4356', '-17496'),
        array('-3372', '-16970'),
        array('-9246', '-15878'),
        array('-8776', '-12829'),
        array('-6946', '-11358'),
        array('-5680', '-11077'),
        array('-3398', '-17730'),
        array('-9238', '-20292'),
        array('-7722', '-10815'),
        array('-8513', '-17697'),
        array('-3880', '-15701'),
        array('-2879', '-15119'),
        array('-6452', '-11831'),
        array('-7750', '-15375'),
        array('-2860', '-15915'),
        array('-9756', '-17692'),
        array('-9482', '-14355'),
        array('-5968', '-10815'),
        array('-8723', '-20051'),
        array('-4892', '-12556'),
        array('-9748', '-18446'),
        array('-5463', '-17202'),
        array('-8286', '-12120'),
        array('-8764', '-13601'),
        array('-3100', '-16733'),
        array('-2584', '-14630'),
        array('-4391', '-20283'),
        array('-2866', '-20283'),
        array('-4692', '-14630'),
        array('-7182', '-15701'),
        array('-7173', '-12849'),
        array('-9297', '-14857'),
        array('-5964', '-15701'),
        array('-7195', '-15180'),
        array('-6431', '-13383'),
        array('-6953', '-12594'),
        array('-9261', '-13601'),
        array('-4138', '-15889'),
        array('-4941', '-18490'),
        array('-4683', '-15385'),
        array('-8749', '-11358'),
        array('-5411', '-11589'),
        array('-7724', '-13367'),
        array('-6987', '-16205'),
        array('-6970', '-18231'),
        array('-3928', '-16970'),
        array('-5936', '-15878'),
        array('-3088', '-10780'),
        array('-8993', '-15903'),
        array('-8735', '-20230'),
        array('-8022', '-19218'),
        array('-7178', '-15903'),
        array('-6409', '-17417'),
        array('-3594', '-15903'),
        array('-5243', '-11067'),
        array('-7938', '-17928'),
        array('-6747', '-15958'),
        array('-5959', '-11067'),
        array('-7075', '-11067'),
        array('-9809', '-14937'),
        array('-6175', '-17417'),
        array('-6912', '-16970'),
        array('-6724', '-15933'),
        array('-9730', '-12346'),
        array('-4682', '-17417'),
        array('-9240', '-15139'),
        array('-8778', '-18996'),
        array('-8774', '-11358'),
        array('-9797', '-14429'),
        array('-7174', '-14902'),
        array('-4640', '-14368'),
        array('-6661', '-11831'),
        array('-8971', '-14170'),
        array('-3399', '-12346'),
        array('-4134', '-19990'),
        array('-5898', '-16657'),
        array('-5941', '-14914'),
        array('-6202', '-16915'),
        array('-4651', '-20051'),
        array('-3101', '-12300'),
        array('-7001', '-17454'),
        array('-7774', '-12594'),
        array('-9218', '-15128'),
        array('-8992', '-15119'),
        array('-9270', '-19023'),
        array('-7717', '-18977'),
        array('-5973', '-17468'),
        array('-9237', '-11847'),
        array('-7521', '-13063'),
        array('-2837', '-14630'),
        array('-7196', '-11097'),
        array('-5664', '-11324'),
        array('-4360', '-16470'),
        array('-3589', '-14379'),
        array('-2094', '-13107'),
        array('-5456', '-11041'),
        array('-5159', '-11831'),
        array('-3116', '-13068'),
        array('-3890', '-11781'),
        array('-9500', '-14429'),
        array('-20480', '-19784'),
        array('-5435', '-11303'),
        array('-4390', '-11303'),
        array('-10066', '-11303'),
        array('-4913', '-11303'),
        array('-5980', '-11303'),
        array('-10055', '-11303'),
        array('-4893', '-12058'),
        array('-9257', '-12058'),
        array('-2367', '-16647'),
        array('-2364', '-14345'),
        array('-21438', '-16647'),
        array('-7426', '-11861'),
        array('-4098', '-14135')
    );
    //特殊拼音库
    private $__notInPinyinConfig = array(
        array('-7761', 'cen')
    );
    //特殊，字库无法正常识别的汉字，进行映射特殊处理
    private $__NotReadWordConfig = array(
        '��' => '磐',
        '�E'  => '零',
        '藁'   => '高',
        '栾'   => '乱',
        '��' => '狮'
    );

    /**
     * 取得某一编码对应的拼音字符(内部调用方法)
     *
     * @param string $num
     * @return string
     */
    private function getOne($num) {
        $pinyin_config = $this->pinyin_config;
        if ($num > 0 && $num < 160) {
            return chr($num);
        } elseif ($num < -20319 || $num > -10247) {
            return "";
        } else {
            for ($i = count($pinyin_config) - 1; $i >= 0; $i--) {
                if ($pinyin_config[$i][1] <= $num) {
                    break;
                }
            }
            return $pinyin_config[$i][0];
        }
    }

    /**
     * 取得汉字对应的拼音全简拼
     *
     * @param string $str
     * @return array $spell
     */
    public function getPinYin($str, $isUTF8 = true) {
        if($isUTF8) {
            $str = $this->_U2_Utf8_Gb($str);
        }
        
        $str = trim($str);
        $spell = array();
        //特殊字符转换 start
        $specialCode = array();
        $specialToRight = array();
        $notInCode = $notPinYin = array();
        //转义无法正确识别的汉字
        foreach ($this->__NotReadWordConfig as $k => $v) {
            if (in_array($k, array_keys($this->__NotReadWordConfig))) {
                $str = str_replace($k, $v, $str);
            }
        }
        foreach ($this->__special_config as $sp) {
            $specialCode[] = $sp[0];
            $specialToRight[$sp[0]] = $sp[1];
        }
        foreach ($this->__notInPinyinConfig as $nIn) {
            $notInCode[] = $nIn[0];
            $notPinYin[$nIn[0]] = $nIn[1];
        }
        //特殊字符转换 end
        $full_spell = "";
        $short_spell = "";

        for ($i = 0; $i < strlen($str); $i++) {
            $p = ord(substr($str, $i, 1));
            if ($p > 160) {
                $q = ord(substr($str,  ++$i, 1));
                $p = $p * 256 + $q - 65536;
            }
            //echo $p."<br>"; 
            //不需处理的特殊符号ASCII码，如(),.（）等
            $specialChars = array_merge(range(30, 47), range(58, 63));
            if (in_array($p, $specialChars)) {
                continue;
            }

            //特殊字符替换
            if (in_array($p, $specialCode))
                $p = $specialToRight[$p];

            $one_spell = $this->getOne($p);
            //特殊字符处理
            if (!$one_spell) {
                if (in_array($p, $notInCode))
                    $one_spell = $notPinYin[$p];
            }

            $full_spell .= $one_spell;
            $short_spell .= substr($one_spell, 0, 1);
        }
        $spell['full'] = strtolower($full_spell);
        $spell['short'] = strtolower($short_spell);
        return $spell;
    }
    
    private function _U2_Utf8_Gb($_C) {
        $_String = '';
        if($_C < 0x80) {
            $_String .= $_C;
        }
        elseif($_C < 0x800) {
            $_String .= chr(0xC0 | $_C>>6);
            $_String .= chr(0x80 | $_C & 0x3F);
        }
        elseif($_C < 0x10000) {
            $_String .= chr(0xE0 | $_C>>12);
            $_String .= chr(0x80 | $_C>>6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }
        elseif($_C < 0x200000) {
            $_String .= chr(0xF0 | $_C>>18);
            $_String .= chr(0x80 | $_C>>12 & 0x3F);
            $_String .= chr(0x80 | $_C>>6 & 0x3F);
            $_String .= chr(0x80 | $_C & 0x3F);
        }
        return @iconv('UTF-8', 'GB2312//IGNORE', $_String);
    }
}

?>