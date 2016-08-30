<?php
namespace  ptrnov\fusionchart;

use Yii;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\base\Widget;
use yii\helpers\Html;
use yii\web\View;
use yii\base\Exception;
   
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
	
	public $dataArray='';
	public $dataField='';
	public $type='';
	public $renderid='';
	public $chartOption='';
	
	
	public function run()	{		
		$html='<div id="'.$this->renderid.'"></div>';
		echo $html;
		$this->registerClientScript($this->renderid);
		
		//return self::chartOption($this->chartOption);
		print_r(self::setProvider($this->dataArray,$this->dataField));
	}	
	
	 /**
     * @var array $dataModel, data value chart
	 * field['label','value'], normaly value is numeric
     * Defaults to `array`.
	 * Convert Array to Json 
     */
	private static function setProvider($aryModel=[],$field=[]){
		//error hendling is not array
		if (!is_array($aryModel)) {
             throw new Exception("Invalid dataArray, please source array 'dataArray'=>[array]");
        }
		//error hendling is not array
		if (!is_array($field)) {
             throw new Exception("Invalid dataField, check your array 'dataField'=>['fieldName1','fieldName2']");
        }
		$dataProvider= new ArrayDataProvider([
			'allModels'=>$aryModel,
			// 'allModels'=>\Yii::$app->db->createCommand("	
				// SELECT id, username FROM userss
			// ")->queryAll(), 
		]);	
		$dataProviderModel=$dataProvider->getModels();
		foreach($dataProviderModel as $key => $value){
			//error hendling is offset
			if (isset($value[$field[0]]) && isset($value[$field[1]])){
				$dataContent[]=["label"=>$value[$field[0]],"value"=>$value[$field[1]]];
			}else{
				//return '[]';
				 throw new Exception("Invalid dataField, please check Column Name 'dataField'=>['wrongName','trueName']");
			}
			
		}; 		
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
						"data": '.self::setProvider($this->dataArray,$this->dataField).'	   
					}
				});
				revenueChart.render();
			});
		';

        $view = $this->getView();
       $view->registerJs($script, View::POS_END);
    }
	
}
?>