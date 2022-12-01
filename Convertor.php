<?php
    class UnitConverter {

        private $units = array();
        private $unit_system = array();
        private $unit_types = array();

        function __construct($amount = 0){
            $this->unit_system["METRIC"] = new UnitSystem('Imperial');
            $this->unit_system["IMPERIAL"] = new UnitSystem('Metric');

            $this->unit_types["TEMPERATURE"] = new UnitType("Temperature");
            $this->unit_types["LENGTH"] = new UnitType("Length");
            $this->unit_types["MASS"] = new UnitType("Mass");

            //Temperature
            $this->units["KELVIN"] = new Unit($this->unit_types['TEMPERATURE'],"K",$this->unit_system['METRIC'],"Kelvin",true);
            $this->units["FAHRENHEIT"] = new Unit($this->unit_types['TEMPERATURE'],"F",$this->unit_system['IMPERIAL'],"Fahrenheit",false,$this->units["KELVIN"],(5.0/9.0*($amount-32.0)) + 273.0,(9.0/5.0)*($amount-273.0)+32.0);
            $this->units["CELSIUS"] = new Unit($this->unit_types['TEMPERATURE'],"C",$this->unit_system['METRIC'],"Celsius",false,$this->units['KELVIN'],$amount + 273.15,$amount-273.15);
            // Mass
            $this->units["KILOGRAM"] = new Unit($this->unit_types['MASS'],"kg",$this->unit_system['METRIC'],"Kilogram",true);
            $this->units["OUNCE"] = new Unit($this->unit_types['MASS'],"oz",$this->unit_system['IMPERIAL'],"Ounce",false,$this->units['KILOGRAM'],0.0283495,35.274);
            $this->units["POUND"] = new Unit($this->unit_types['MASS'],"lb",$this->unit_system['IMPERIAL'],"Pound",false,$this->units['KILOGRAM'],0.453592,2.20462);
            $this->units["STONE"] = new Unit($this->unit_types['MASS'],"st",$this->unit_system['IMPERIAL'],"Stone",false,$this->units['KILOGRAM'],6.35029,0.157473);
            $this->units["IMPERIAL_TON"] = new Unit($this->unit_types['MASS'],"tn",$this->unit_system['IMPERIAL'],"Imperial Tonne",false,$this->units['KILOGRAM'], 0.157473, 0.000984207);
            $this->units["GRAM"] = new Unit($this->unit_types['MASS'],"g",$this->unit_system['METRIC'],"Gram",false,$this->units['KILOGRAM'], 0.001, 1000.0);
            $this->units["TONNE"] = new Unit($this->unit_types['MASS'],"t",$this->unit_system['METRIC'],"Tonne",false,$this->units['KILOGRAM'], 1000.0, 0.001);
             //LENGTH
            $this->units["METRE"] = new Unit($this->unit_types['LENGTH'],"m",$this->unit_system['METRIC'],"Metre",true);
            $this->units["INCH"] = new Unit($this->unit_types['LENGTH'],"in",$this->unit_system['IMPERIAL'],"Inch",false,$this->units['METRE'],0.0283495,35.274);
            $this->units["FOOT"] = new Unit($this->unit_types['LENGTH'],"ft",$this->unit_system['IMPERIAL'],"Feet",false,$this->units['METRE'],0.453592,2.20462);
            $this->units["YARD"] = new Unit($this->unit_types['LENGTH'],"yd",$this->unit_system['IMPERIAL'],"Yard",false,$this->units['METRE'],6.35029,0.157473);
            $this->units["MILE"] = new Unit($this->unit_types['LENGTH'],"mi",$this->unit_system['IMPERIAL'],"Mile",false,$this->units['METRE'], 0.157473, 0.000984207);
            $this->units["MILLIMETRE"] = new Unit($this->unit_types['LENGTH'],"mm",$this->unit_system['METRIC'],"Millimetre",false,$this->units['METRE'], 0.001, 1000.0);
            $this->units["CENTIMETRE"] = new Unit($this->unit_types['LENGTH'],"cm",$this->unit_system['METRIC'],"Centimetre",false,$this->units['METRE'], 0.01, 100.0);
            $this->units["KILOMETRE"] = new Unit($this->unit_types['LENGTH'],"km",$this->unit_system['METRIC'],"Kilometre",false,$this->units['METRE'], 1000.0, 0.001);
        }

        public function getByUnitType($type){
           $return_units = array();  
            foreach ($this->units as $key => $value) {
                if($value->getType()->getName() == $type){
                    array_push($return_units,$value->getName());
                }
            }
            return $return_units;  
        }
        
        public function getByName($name) {
            $return_unit = null;  
            foreach ($this->units as $key => $value) {

                if($value->getName() == $name){
                    $return_unit = $value;
                }
            }
            return $return_unit;  
        }   

        public function getTypes(){
            $return_units = array();  
            foreach ($this->unit_types as $key => $value) {
                array_push($return_units,$value->getName());
            }
            return $return_units;
        }   
    }

    class UnitSystem {
        private $name;

        function __construct($name) {
            $this->name = $name;
        }

        public function getName() {
            return $this->name;
        }
    }

    class Unit{
        private $type;
        private $unit;
        private $system;
        private $name;
        private $reference;
        private $to;
        private $from;
        private $base;

        /**
         * Creates a new Unit
         * @param type unit category
         * @param unit short name for this unit
         * @param system unit system to use (imperial/metric)
         * @param name unit name
         * @param reference the base unit of this category to convert to
         */
        
        function __construct($type, $unit, $system, $name,$base, $reference=0, $converterto = null, $converterfrom= null){
            $this->type = $type;
            $this->unit = $unit;
            $this->system = $system;
            $this->name = $name;
            $this->reference = $reference;
            if($converterto != null){
                if($this->type->getName() != "Temperature"){
                    $this->to = new MultiplicationConverter($converterto);
                }else{
                    $this->to = new TempConverter($converterto);
                }
            }
            if($converterfrom != null){
                if($this->type->getName() != "Temperature"){
                    $this->from = new MultiplicationConverter($converterfrom);
                }else{
                     $this->from = new TempConverter($converterfrom);
                }
            }
            $this->base = $base;
        }

        public function getUnit(){
            return $this->unit;
        }

        public function getType(){
            return $this->type;
        }

        public function getSymbol() {
            return $this->unit;
        }

        public function getSystem() {
            return $this->system;
        }

        public function getName() {
            return $this->name;
        }

        public function getReference() {
            return $this->reference;
        }

        public function getTo() {
            return $this->to;
        }

        public function getFrom() {
            return $this->from;
        }

        public function isBase() {
            return $this->base;
        }
    }

    class UnitType {
        private $name;

        function __construct($name) {
            $this->name = $name;
        }

        public function getName() {
            return $this->name;
        }
    }

    interface Converter {
        public function convert($amount);
    }

    class MultiplicationConverter implements  Converter{

        private $fac = 0;

        public function __construct($factor){
            $this->fac = $factor;
        }

        public function convert($amount) {
            return $amount * $this->fac;
        }

    }

    class TempConverter implements  Converter{

        private $fac = 1;

        public function __construct($factor){
            $this->fac = $factor;
        }

        public function convert($amount) {
            return $this->fac;
        }

    }

    class Convertor {
    
        private $unit_from;
        private $unit_to;
        private $amount;
        private $answer;
        private $unit_type;
        private $uc;

        public function __construct($unit_from=null,$unit_to=null,$amount=null) {
            $this->uc = new UnitConverter($amount); 
            if($unit_from != null & $unit_to != null & $amount != null){
                $this->setUnitFrom($unit_from);
                $this->setUnitTo($unit_to);
                $this->amount = $amount;

                $based;
                if($this->unit_from->isBase()){
                    $based = $amount;
                }
                else {
                    $based = $this->unit_from->getTo()->convert($amount);
                }
                $result;
                if($this->unit_to->isBase()){
                    $result = $based;
                }
                else {
                    switch ($this->unit_to->getName()) {
                        case "Celsius": 
                            if($this->unit_from->getName() == 'Celsius'){
                                $result = $based - 273; break;
                            }else{
                                $result = $based - 273.15; break;
                            }
                        case "Fahrenheit": 
                            if($this->unit_from->getName() == 'Fahrenheit'){
                                $result = (9.0/5.0)*($this->amount)+32.0;
                            }else{
                                $result = ($this->amount - 273.15) * 9/5 + 32;
                            }
                         break;
                        default:  $result = $this->unit_to->getFrom()->convert($based); break;
                    }                  
                }
                $this->answer = $result;
            }
        }

        public function convert(){
            $return_array = array(
                "from" => $this->unit_from->getName(),
                "to" => $this->unit_to->getName(),
                "amount" => $this->amount,
                "answer" => $this->answer,
                "type" => $this->unit_from->getType()->getName()
            );
            return $return_array;            
        }
       
        public function getUnitFrom() {
            return $this->unit_from;
        }
        public function getUnitTo() {
            return $this->unit_to;
        }
        public function getAnswer() {
            return $this->answer;
        }
        public function getAmount() {
            return $this->amount;
        }
        public function getUnitType() {
            return $this->unit_type;
        }
        
        public function setUnitFrom($unit_from) {
            $this->unit_from = $this->uc->getByName($unit_from);
        }

        public function setUnitTo($unit_to) {
            $this->unit_to = $this->uc->getByName($unit_to); 
        }
        
    }
?>
