<?php

/**
 * @package   yii2-grid
 * @author    Kartik Visweswaran <kartikv2@gmail.com>
 * @copyright Copyright &copy; Kartik Visweswaran, Krajee.com, 2014 - 2016
 * @version   3.1.2
 */

namespace ptrnov\fusionchart;

use yii\web\AssetBundle;

/**
 * Asset bundle for GridView Widget
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @since 1.0
 */
class ChartAsset extends AssetBundle
{
	public $sourcePath = '@vendor/ptrnov/yii2-fusionchart/assets';
	public $css=[
		'css/ptr-load.css',
	];
    public $js = [
       'js/fusioncharts.js',		
       'js/fusioncharts.widgets.js',
       'js/fusioncharts.charts.js',
       'js/fusioncharts.gantt.js',
   ];
	public $jsOptions = ['position' => \yii\web\View::POS_END];
}
