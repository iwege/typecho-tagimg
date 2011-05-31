<?php
/**
 * 生成tag对应的图片 <span style="color:#999999">[ 根据map文件来判断 ]</span>
 *
 * @package TagImg
 * @author iwege
 * @version 1.0.4
 * @dependence 10.8.15
 * @link http://www.iwege.com
 */
class TagImg_Plugin implements Typecho_Plugin_Interface
{

    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
		if(function_exists('file_get_contents')&&function_exists('is_readable')){
			Typecho_Plugin::factory('Widget_Abstract_Contents')->content  = array('TagImg_Plugin', 'parse');
			return _t('Tag Image 插件启动成功，系统将根据指定的tag名调用图片');
		}else{
			return _t('Tag Image 插件依赖的函数不存在，不能成功激活');
		}
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){
        return _t('Tag Image 插件已被禁用，系统将不再调用Tag相关的图片');
    }

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form){}


    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function parse($text,$widget)
    {
        $tags = $widget->tags;
        $tagImg = TagImg_Plugin::getContentImg($tags);
        if ($tagImg) {
            return $tagImg.$text;
        }
        return $text;
    }

     /**
     * 根据标签获取文章的小图
      * 
     * @author iwege
     * @access public
     * @return void
     */
    public static function getContentImg($tags){
        require('TagImg/Map.php');
        $result = '';
        if ($tags) {
               foreach ($tags as $tag) {
                    if (in_array($tag['name'], $images)) {
                        $result .= '<img alt="'.$tag['name'].'.jpg" src="'.Typecho_Widget::widget('Widget_Options')->siteUrl.'./usr/uploads/'.$tag['name'].'.jpg" />';
                    }
                }
            if($result){
                return '<p>'.$result.'</p>';
            }
            return false;
        }
    }
    
    /**
     * 获取文件内容
     *
     * @access public
     * @return string
     */
    public static function getContent()
    {
        $path = __TYPECHO_ROOT_DIR__.__TYPECHO_PLUGIN_DIR__.'/TagImg/Map.php';
        return htmlspecialchars(file_get_contents($path));
    }
    /**
     * 获取文件是否可读
     *
     * @access public
     * @return string
     */
    public static function isReadable()
    {
       $path = __TYPECHO_ROOT_DIR__  . __TYPECHO_PLUGIN_DIR__.'/TagImg/Map.php' ;
       return is_readable($path);
    }
}
