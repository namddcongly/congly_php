<?php
if (defined(IN_JOC)) die("Direct access not allowed!");

class Page
{
    private static $doctype = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">';
    private static $title;
    private static $keyword;
    private static $description;
    private static $css;
    private static $js;
    private static $meta;
    private static $ga;
    private static $gadsense;
    private $header;
    private $footer;

    private $cached;
    private $content_cached;
    private $module_cached;
    private $host;

    function __construct()
    {
        if ($_SERVER['HTTP_HOST'] == 'old.congly.com.vn') {
            $this->host = 'http://' . $_SERVER['HTTP_HOST'];
        } else {
            $this->host = 'https://' . $_SERVER['HTTP_HOST'];
        }

    }

    public static function setMeta($meta)
    {
        self::$meta .= $meta;
    }

    public static function setGA($ga)
    {
        self::$ga .= $ga;
    }

    public static function setGadsense($adsense)
    {
        self::$gadsense .= $adsense;
    }

    /**
     * dang ky file su dung
     *
     * @param ten file $file_name
     * @param duong dan $path
     * @param vi tri $where
     * @param kieu fil $file_type
     */
    public static function registerFile($file_name, $path, $where = "footer", $file_type = "js", $hack = "")
    {
        if ($file_type != "js" && $file_type != "css")
            return;

        if ($file_type == "js") {
            self::$js[$where][$file_name]['path'] = ROOT_URL . $path;
            if ($hack != "")
                self::$js[$where][$file_name]['hack'] = $hack;
        } else {
            self::$css[$where][$file_name]['path'] = ROOT_URL . $path;

            if ($hack != "")
                self::$css[$where][$file_name]['hack'] = $hack;
        }
    }


    // fix
    // public function setHeader($title = '', $keyword = '', $description = '')
    public static function setHeader($title = '', $keyword = '', $description = '')
    {
        self::$title = htmlspecialchars($title);
        self::$keyword = htmlspecialchars($keyword);
        self::$description = htmlspecialchars($description);
    }


    // public function setDoctype($str = '')
    public function setDoctype($str = '')
    {
        self::$doctype = $str;
    }
    // endfix

    /**
     * return header of page
     *
     */
    public function getHeader()
    {
        $css = self::$css;
        $js = self::$js;
        $url = $_SERVER['REQUEST_URI'];
        $this->header = self::$doctype;
        $this->header .= '<head>';
        $this->header .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $this->header .= '<base href="' . ROOT_URL . '" />';
        $this->header .= '<!-- AMP Analytics --><script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>';
        $this->header .= '<meta http-equiv="REFRESH" content="10800" />';
        $this->header .= '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
        $this->header .= '<title>' . self::$title . '</title>';
        $this->header .= '<meta name="keywords" content="' . self::$keyword . '" />';
        $this->header .= '<meta name="description" content="' . self::$description . '" />';
        $this->header .= '<link rel="canonical" href="https://congly.vn' . $url . '"/>';
        $this->header .= $this->defaultMeta();
        $this->header .= self::$meta;
        $head_css = $css['header'];
        $leng = count($head_css);

        if ($leng > 0)
            foreach ($head_css as $key => $hs) {
                if (isset($hs['hack']))
                    $this->header .= $hs['hack'] . '<link rel="stylesheet" type="text/css" href="' . $hs['path'] . '?ver=' . JS_VERSION . '" />' . ($hs['hack'] != "" ? "<![endif]-->" : "");
                else
                    $this->header .= '<link rel="stylesheet" type="text/css" href="' . $hs['path'] . '?ver=' . CSS_VERSION . '" />';
            }

        $leng = count($foot_css = isset($css['footer']) ? $css['footer'] : array());
        if ($leng > 0)
            foreach ($foot_css as $key => $fs)
                $this->footer .= '<link rel="stylesheet" type="text/css" href="' . $fs['path'] . '" />';

        //$leng = count($head_js);
        $leng = count($head_js = isset($js['header']) ? $js['header'] : array());
        // endfix

        if ($leng > 0)
            foreach ($head_js as $key => $hj) {
                if (isset($hj["hack"]))
                    $this->header .= $hj["hack"] . '<script  type="text/javascript" src="' . $hj['path'] . '"></script><![endif]-->';
                else
                    $this->header .= '<script  type="text/javascript" src="' . $hj['path'] . '"></script>';
            }
        $foot_js = array();
        if (isset($js['footer']))
            $foot_js = $js['footer'];

        $leng = count($foot_js);
        if ($leng > 0)
            foreach ($foot_js as $key => $fj) {
                if (isset($fj["hack"]))
                    $this->footer .= $fj["hack"] . '<script  type="text/javascript" src="' . $fj['path'] . '"></script><![endif]-->';
                else
                    $this->footer .= '<script type="text/javascript" src="' . $fj["path"] . '"></script>';
            }
        $this->header .= '<script data-ad-client="ca-pub-2266829271052118" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>';
        $this->header .= '<script type="text/javascript">var root_url = "' . ROOT_URL . '";</script>';
        $this->header .= '<link rel="shortcut icon" href="favicon.ico" />';
        $this->header .= "
			<script>
			(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
			(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
			m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
			})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
			ga('create', 'UA-53202552-1', 'congly.vn');
			ga('send', 'pageview');
			ga('create', 'UA-53202552-21', 'congly.vn', {'name': 'newTracker'});
			ga('newTracker.send', 'pageview');
			</script>
		";
        $this->header .= $this->setAdsGoogleAdsense();
        $this->header .= '
        <link type="text/css" rel="stylesheet" href="' . $this->host . '/webskins/skins/news/css/jquery.mmenu.all.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"
        integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <script type="text/javascript" src="' . $this->host . '/webskins/skins/news/js/jquery-1.8.0.js"></script>
        <script src="' . $this->host . '/webskins/skins/news/js/popbox.js"></script>
        <script type="text/javascript" src="' . $this->host . '/webskins/skins/news/js/jquery.tinyscrollbar.js"></script>';
        if ($_GET['print'])
            $this->header .= '<link type="text/css" rel="stylesheet" href="' . $this->host . '/webskins/skins/news/css/style.css?ver=' . time() . '" />';
        else
            $this->header .= '<link type="text/css" rel="stylesheet" href="' . $this->host . '/webskins/skins/news/css/responsive.css?ver=' . time() . '" />';

        $this->header .= '
        <script type="text/javascript">
            $(document).ready(function(){
                cachemenu = \'<nav id="menu">\'+$(\'nav\').html()+\'<\/nav>\';
                function runMenuResponsive(){
                    if($(window).width()<768){
                        $(function() {
                            $(\'nav#menu\').mmenu();
                        });
                    }else{

                        $(\'nav\').remove();
                        $(\'div.header-menu\').append(cachemenu);
                    }

                }
                runMenuResponsive();
                $(window).resize(function(){
                    runMenuResponsive();
                });
                $(window).scroll(function(){
                    if ($(this).scrollTop() > 600) {
                        $(\'.scrollToTop\').fadeIn();
                    } else {
                        $(\'.scrollToTop\').fadeOut();
                    }
                });
                $(\'.scrollToTop\').click(function(){
                    $(\'html, body\').animate({scrollTop : 0},800);
                    return false;
                });
                $(\'.popbox\').popbox();
                $(\'#scrollbar1\').tinyscrollbar();
            });
        </script>';
        $this->header .= self::$ga;
        $this->header .= self::$gadsense;
        $this->header .= '</head>';
        $this->header .= '<body>';
        return $this->header;
    }

    /**
     * get body of page
     *
     */
    public function getBody($portal, $page)
    {
        $this->cached = SystemIO::get('cached', 'int', 0);

        //get info page
        $pageObj = SystemIO::createObject('PageModel');

        $page_info = $pageObj->getOnePage("*", 0, "name='" . $page . "'");

        if (isset($page_info['name'])) {
            $layout_name = "admin_platform";
            //get layout
            if ((int)$page_info['layout_id'] > 0) {
                $layoutObj = SystemIO::createObject('LayoutModel');

                $layout_page = $layoutObj->getOneLayout("name,blocks", 0, "id=" . $page_info['layout_id']);

                if (isset($layout_page['name']))
                    $layout_name = $layout_page['name'];
            }

            $regions = explode("|", $layout_page['blocks']);

            //get module of page
            $moduleObj = SystemIO::createObject('ModuleModel');

            $modules = $moduleObj->getModuleOfPage($page_info['id'], $page_info['master_id']);

            //show module
            $blocks = array();

            $tmpmodules = array();

            if (count($modules) && is_array($modules)) {
                $len = count($regions);
                if ($len > 0)
                    for ($i = 0; $i < $len; ++$i)
                        foreach ($modules as $tmp)
                            if ($tmp['possition'] == $regions[$i])
                                $tmpmodules[] = $tmp;

                foreach ($tmpmodules as $module) {
                    $mode = '<?php require(\'' . APPLICATION_PATH . $module['path'] . '\');';

                    $mode .= '$_class = "' . $module['name'] . '";$blockObj = new $_class;echo $blockObj->index();?>';

                    if ($this->cached)
                        $this->module_cached[$module['possition']] .= $mode;

                    require(APPLICATION_PATH . $module['path']);

                    $_class = $module['name'];

                    $blockObj = new $_class;
                    if (!isset($blocks[$module['possition']])) $blocks[$module['possition']] = '';

                    $blocks[$module['possition']] .= $blockObj->index();
                }
            }

            $fp = fopen(LAYOUT_PATH . $layout_name . ".php", "r");

            $body = stream_get_contents($fp);

            $this->content_cached = $body;

            fclose($fp);

            if (count($blocks) > 0) {
                foreach ($blocks as $key => $value)
                    $body = str_replace("[[$key]]", $value, $body);

                if (is_array($this->module_cached) && count($this->module_cached) > 0) {
                    foreach ($this->module_cached as $key => $mc)
                        $this->content_cached = str_replace("[[$key]]", $mc, $this->content_cached);
                }
            } else
                $body = "";

            return $body;
        }

        return false;
    }

    /**
     * get footer of page
     *
     */
    public function getFooter()
    {
        return $this->footer . '</body></html>';
    }

    /**
     * lay noi dung html
     *
     */
    public function getContent($portal, $page)
    {
        if (file_exists(CACHE_PATH . 'system' . DS . $portal . DS . $page . '.php')) {
            ob_start();

            require(CACHE_PATH . 'system' . DS . $portal . DS . $page . '.php');

            $temp_html = ob_get_contents();

            ob_clean();

            $html = $this->getHeader() . $temp_html;

            $html .= $this->getFooter();

            return $html;
        }

        $body = $this->getBody($portal, $page);

        if ($body !== false) {
            $html = $this->getHeader();
            flush();
            $html .= $body;
            $html .= $this->getFooter();
            if ($this->cached) {
                $fp = fopen(CACHE_PATH . 'system' . DS . $portal . DS . $page . '.php', "w+");
                $this->content_cached = preg_replace('/\>(\s\s+)\</', '><', $this->content_cached);
                fwrite($fp, $this->content_cached);

                fclose($fp);
            }

            return $html;
        } else {
            return "Không tồn tại trang bạn yêu cầu";
        }

    }

    public function defaultMeta()
    {
        return '
        <meta http-equiv="window-target" content="_top">
        <meta http-equiv="pragma" content="no-cache">
        <meta http-equiv="expires" content="-1">
        <meta http-equiv="cache-control" content="no-cache">
        <meta name="Googlebot" content="index,follow,NOODP">
        <meta name="robots" content="noodp,index, follow">
        <meta name="revisit-after" content="1 days">
        <meta name="abstract" content="congly.vn Báo điện tử tin tức an ninh pháp luật số 1 Việt Nam" />
        <meta name="distribution" content="Global" />
        <meta name="author" itemprop="author" content="congly.vn" />
        <meta name="news_keywords" content="Tin tuc, tin moi, tin tức mới nhất, tin tuc moi, tin tuc 24h, VN, doc bao, đọc báo, báo, tin mới nhất" />
        <meta http-equiv="refresh" content="1200" />
        <meta http-equiv="X-UA-Compatible" content="IE=8"/>
        <meta name="RATING" content="GENERAL" />
        <meta name="contact" content="conglydientu@congly.vn"/>
        <meta name="DC.identifier" content="//congly.vn" />
        <meta name="DC.language" content="VN" />
        <meta name="geo.region" content="VN-HN" />
        <meta name="geo.placename" content="48A Ly Thuong Kiet, Hoan Kiem, Ha Noi" />
        <meta name="geo.position" content="14.058324;108.277199" />
        <meta name="ICBM" content="14.058324, 108.277199" />';
    }

    public function setAdsGoogleAdsense()
    {
        return '';
    }
}

?>