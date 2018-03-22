Fusonchart for Yii2
=======================
widget render Chart with fusonchart

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist ptrnov/yii2-fusionchart "*"
```

or add

```
"ptrnov/yii2-fusionchart": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

```php
	/**
	 * RUNNING IN FUSIONCHART TYPE
	 * column2d,column3d,bar2d
	 * Type,  Ref: http://www.fusioncharts.com/dev/demos/chart-gallery.html
	 * color, Ref: http://dmcritchie.mvps.org/excel/colors.htm
	*/

	//Controllers
	use ptrnov\fusionchart\Chart;
	//use ptrnovlab\models\Userlogin;
	
	public function actionIndex()
    {
		$model =Userlogin::find()->all(); 				// your Model, example from class user
		$testChart= Chart::Widget([
			'dataArray'=>$model,						//array scource model or manual array or sqlquery
			'dataField'=>['username','id'],				//field['label','value'], normaly value is numeric
			'type'=>'column3d',							//Chart Type 
			'renderid'=>'chartContainer',				//unix name render
			'chartOption'=>[				
					'caption'=>'judul Header',			//Header Title
					'subCaption'=>'judul Sub',			//Sub Title
					'xaxisName'=>'Month',				//Title Bawah/ posisi x
					'yaxisName'=>'Jumlah', 				//Title Samping/ posisi y									
					'theme'=>'fint',					//Theme
					'palettecolors'=> "#583e78,#008ee4,#f8bd19,#e44a00,#6baa01,#ff2e2e",
					'bgColor'=> "#ffffff",				//color Background / warna latar 
					'showBorder'=> "0",					//border box outside atau garis kotak luar
					'showCanvasBorder'=> "0",			//border box inside atau garis kotak dalam	
			],
		]);
			
		return $this->render('index',[
				'testChart'=>$testChart
			]);
	}
	 
	//EXAMPLE 2 
	
	public function actionIndex()
    {
		$testChart= Chart::Widget([
			'urlSource'=> 'https://your_api',
			'metode'=>'POST',
			'param'=>[
				'BULAN'=>date("m"),
				'TAHUN'=>date("Y")
			],
			'userid'=>'piter@lukison.com',
			'dataArray'=>'[]',//$actionChartGrantPilotproject,				//array scource model or manual array or sqlquery
			'dataField'=>'[]',//['label','value'],							//field['label','value'], normaly value is numeric
			'type'=>'msline',//msline//'bar3d',//'gantt',					//Chart Type 
			'renderid'=>'msline-sales-weekly',								//unix name render
			'autoRender'=>true,
			'width'=>'100%',
			'height'=>'300px',
			//'chartOption'=> api server side
		]);	 
			
		return $this->render('index',[
				'testChart'=>$testChart
			]);
	} 
	 
	
	//=== UPDATE FUSIONCHAT WITH AJAX  ===
	$this->registerJs("
		$(document).ready(function (){
			var renderid = document.getElementById('msline-sss-hour-3daystrafik');
			var spnIdRenderid= renderid.getElementsByTagName('span');
			var chartUpdate= spnIdRenderid[0].id; 
			//console.log(chartUpdate);	
			var initUpdateChart = document.getElementById(chartUpdate);					
			$.ajax({
				url: 'https://your_api',
				type: 'POST',
				data: {'TGL':'".date("Y-m-d")."'},
				dataType:'json',
				success: function(data) {
					//===UPDATE CHART ====
					if (data['dataset'][0]['data']!==''){							
						initUpdateChart.setChartData({
							chart: data['chart'],
							categories:data['categories'],
							dataset: data['dataset']
						});	
					}else{
						initUpdateChart.setChartData({
							chart: data['chart'],
							categories:data['categories'],
							data:[{}]
						});						
					}					
				}			   
			}); 
		});
	",$this::POS_END);
	
	
	//views index
	use ptrnov\fusionchart\ChartAsset;
	ChartAsset::register($this);
	
	<div><?=$testChart?></div>
```

