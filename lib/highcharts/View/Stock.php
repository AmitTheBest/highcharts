<?php
namespace highcharts;
class View_Stock extends \View {
    public $options=array(
        'rangeSelector'=>array( 'selected'=>1),
        'title'=>array( 'text'=>'Sample Title' ),
    );

    public $series=null;
    function init(){
        parent::init();
        $this->api->jquery->addStaticInclude('//code.highcharts.com/stock/highstock.js');
    }
    function setModel($model, $timestamp_field, $series){
        $model=parent::setModel($model);
        $this->timestamp_field=$timestamp_field;
        $this->series=$series;
        return $model;
    }
    function render(){
        $data=$this->model->getRows();

        foreach($this->series as $field=>$title){

            if(is_numeric($field))$field=$title;

            $sdata=array();

            foreach($data as $row){
                $k=$row[$this->timestamp_field];
                $v=$row[$field];

                if($k instanceof \MongoDate)$k=$k->sec;

                $k=$k*1000;

                $sdata[]=array($k,$v);
            }

            $this->options['series'][]=array(
                'name'=> $title,
                'data'=> $sdata,
            );
        }

        $this->js(true)->highcharts(
            'StockChart',
            $this->options
        );
        return parent::render();
    }
}
