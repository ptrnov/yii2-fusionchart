<?php
namespace  ptrnov\fusionchart;

use Yii;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;
   
   
/**
 * This is Chart Class 
*/
class Chart extends Widget
{
	//title
	const CHAT_CAPTION = 'caption';
	const CHAT_SUBCAPTION = 'subCaption';
	const CHAT_XAXISNAME = 'xAxisName';
	const CHAT_YAXISNAME = 'yAxisName';
	const CHAT_THEME = 'theme';
	
	//warna	
	const CHAT_PALETTECOLORS = 'palettecolors';
	const CHAT_BGCOLOR = 'bgColor';
	
	//border
	const CHAT_SHOWBORDER = 'showBorder';	
	const CHAT_SHOWCANVASBORDER = 'showCanvasBorder';	
	
	public $dataModel=[];
	public $type='';
	public $renderid='';
	public $chartOption='';
	
	
	public function run()	{
		
		 $html='<div id="'.$this->renderid.'"></div>';
			echo $html;
		$this->registerClientScript($this->renderid);
		//return self::chartOption($this->chartOption);
		//return self::aryProvider($this->dataModel);
	}	
	
	 /**
     * @var array $dataModel, data value chart
     * Defaults to `array`.
	 * Convert Array to Json 
     */
	private static function aryProvider($aryModel=[]){
		//LABEL
		foreach($aryModel[0] as $key => $value){
			//$dataLabel=['data'=>['label'=>$key,'value'=>$value]];
			$dataLabel[]=['label'=>$key,'value'=>$value];
			//$data=$data . $data;
		} 
		//CONTENT
		foreach($aryModel as $key => $value){
			$nilai=$key;
			//$dataContent[]=['label'=>$key,'value'=>$value['username']];
			$dataContent[]=['label'=>$value['username'],'value'=>$value['id']];
		}
		$data=['data'=>$dataContent];
		
		//return Json::encode($data);
		return Json::encode($dataContent);
	}
	
	
	 /**
     * @var array $chartOption, data properties chart
     */
	private static function chartOption($opt){		
		if (!is_array($opt)) {
			$chat_opt=[''];
        };
		if (is_array($opt)) { 
			$chat_opt=[];
			foreach ($opt as $key => $vales){
				$keyLabel=strtoupper($key);
				if ($keyLabel=='CAPTION'){
					$chat_header = [self::CHAT_CAPTION =>$vales];
				}elseif ($keyLabel=='SUBCAPTION'){
					$chat_header = [self::CHAT_SUBCAPTION=>$vales];
				}elseif ($keyLabel=='XAXISNAME'){
					$chat_header = [self::CHAT_XAXISNAME=>$vales];
				}elseif ($keyLabel=='YAXISNAME'){
					$chat_header = [self::CHAT_YAXISNAME=>$vales];
				}elseif ($keyLabel=='THEME'){
					$chat_header = [self::CHAT_THEME=>$vales];
				}elseif ($keyLabel=='PALETTECOLORS'){
					$chat_header = [self::CHAT_PALETTECOLORS=>$vales];
				}elseif ($keyLabel=='BGCOLOR'){
					$chat_header = [self::CHAT_BGCOLOR=>$vales];
				}elseif ($keyLabel=='SHOWBORDER'){
					$chat_header = [self::CHAT_SHOWBORDER=>$vales];
				}elseif ($keyLabel=='SHOWCANVASBORDER'){
					$chat_header = [self::CHAT_SHOWCANVASBORDER=>$vales];
				}else{
					$chat_header=[''];
				}				
				$chat_opt= array_merge($chat_opt,$chat_header);
			}
		}		
		return Json::encode($chat_opt);
	}
	
	/**
	* @var id,html id for render
	* Build script chart js
	*/
	public function registerClientScript($id)
    {
       $script = '
			$(document).ready(function () {
			 var revenueChart = new FusionCharts({
					"type": "'.$this->type.'",					
					"renderAt": "'.$id.'",
					"width": "500",
					"height": "300",
					"dataFormat": "json",
					"dataSource": {
					    "chart":'.self::chartOption($this->chartOption).',					 
						"data":'.self::aryProvider($this->dataModel).'					   
					}
				});

				revenueChart.render();
			});
		';

        $view = $this->getView();
       $view->registerJs($script, View::POS_READY);
    }
	
}
?>