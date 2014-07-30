<?php
/* 
    PHP Table Class from Dynamic Web Coding at dyn-web.com
    Copyright 2001-2014 by Sharon Paine
    For demos, documentation and updates, visit http://www.dyn-web.com/code/table_class/

    Released under the MIT license
    http://www.dyn-web.com/business/license.txt
*/

// version date: Feb 2014

class HTML_Table {
    
    public $xhtml = true; // for col tags
    
    private $thead = array();
    private $tfoot = array();
    private $tbody_ar = array();
    
    private $cur_section;
    private $colgroups_ar = array();
    private $cols_ar = array(); // if cols not in colgroup
    
    private $tableStr = '';
    
    function __construct($id = '', $klass = '', $attr_ar = array() ) {
        // add rows to tbody unless addTSection called
        $this->cur_section = &$this->tbody_ar[0];
        
        $this->tableStr = "\n<table" . ( !empty($id)? " id=\"$id\"": '' ) . 
            ( !empty($klass)? " class=\"$klass\"": '' ) . $this->addAttribs( $attr_ar ) . ">\n";
    }
    
/*
  each section collects rows
  when a row is added, add it to the current section 
*/
    function addTSection($sec, $klass = '', $attr_ar = array() ) {
        switch ($sec) {
            case 'thead':
                $ref = &$this->thead;
                break;
            case 'tfoot':
                $ref = &$this->tfoot;
                break;
            case 'tbody':
                $ref = &$this->tbody_ar[ count($this->tbody_ar) ];
                break;
            
            default: // tbody
                $ref = &$this->tbody_ar[ count($this->tbody_ar) ];
        }
        
        $ref['klass'] = $klass;
        $ref['atts'] = $attr_ar;
        $ref['rows'] = array();
        
        $this->cur_section = &$ref;
    }
    
    function addColgroup($span = '', $klass = '', $attr_ar = array() ) {
        $group = array(
            'span' => $span,
            'klass' => $klass,
            'atts' => $attr_ar,
            'cols' => array()
        );
        
        $this->colgroups_ar[] = &$group;
    }
    
    function addCol($span = '', $klass = '', $attr_ar = array()) {
        $col = array(
            'span' => $span,
            'klass' => $klass,
            'atts' => $attr_ar
        );
        
        // in colgroup?
        if ( !empty($this->colgroups_ar) ) {
            $group = &$this->colgroups_ar[ count($this->colgroups_ar) - 1 ];
            $group['cols'][] = &$col;
        } else {
            $this->cols_ar[] = &$col;
        }
        
    }
    
    public function addCaption($cap, $klass = '', $attr_ar = array() ) {
        $this->tableStr.= "<caption" . (!empty($klass)? " class=\"$klass\"": '') .
            $this->addAttribs($attr_ar) . '>' . $cap . "</caption>\n";
    }
    
    private function addAttribs( $attr_ar ) {
        $str = '';
        foreach( $attr_ar as $key=>$val ) {
            $str .= " $key=\"$val\"";
        }
        return $str;
    }
    
    function addRow($klass = '', $attr_ar = array() ) {
        // add row to current section
        $this->cur_section['rows'][] = array(
            'klass' => $klass,
            'atts' => $attr_ar,
            'cells' => array()
        );
        
    }
    
    function addCell($data = '', $klass = '', $type = 'data', $attr_ar = array() ) {
        $cell = array(
            'data' => $data,
            'klass' => $klass,
            'type' => $type,
            'atts' => $attr_ar
        );
        
        if ( empty($this->cur_section['rows']) ) {
            try {
                throw new Exception('You need to addRow before you can addCell');
            } catch(Exception $ex) {
                $msg = $ex->getMessage();
                echo "<p>Error: $msg</p>";
            }
        }
        
        // add to current section's current row's list of cells
        $count = count( $this->cur_section['rows'] );
        $curRow = &$this->cur_section['rows'][$count-1];
        $curRow['cells'][] = &$cell;
    }
    
    private function getRowCells($cells) {
        $str = '';
        foreach( $cells as $cell ) {
            $tag = ($cell['type'] == 'data')? 'td': 'th';
            $str .= ( !empty( $cell['klass'] )? "    <$tag class=\"{$cell['klass']}\"": "    <$tag" ) . 
                    $this->addAttribs( $cell['atts'] ) . ">" . $cell['data'] . "</$tag>\n";
        }
        return $str;
    }
    
    function display() {
        // get colgroups/cols
        $this->tableStr .= $this->getColgroups();
        
        // get sections and their rows/cells
        $this->tableStr .= !empty($this->thead)? $this->getSection($this->thead, 'thead'): '';
        $this->tableStr .= !empty($this->tfoot)? $this->getSection($this->tfoot, 'tfoot'): '';
        
        foreach( $this->tbody_ar as $sec ) {
            $this->tableStr .= !empty($sec)? $this->getSection($sec, 'tbody'): '';
        }
        
        $this->tableStr .= "</table>\n";
        return $this->tableStr;
    }
    
    // get colgroups/cols
    private function getColgroups() {
        $str = '';
        
        if ( !empty($this->colgroups_ar) ) {
            foreach( $this->colgroups_ar as $group ) {
            
                $str .= "<colgroup" . ( !empty($group['span'])? " span=\"{$group['span']}\"": '' ) .
                    ( !empty($group['klass'])? " class=\"{$group['klass']}\"": '' ) . 
                    $this->addAttribs( $group['atts'] ) . ">" . 
                    $this->getCols( $group['cols'] ) . "</colgroup>\n";
            }
        } else {
            $str .= $this->getCols($this->cols_ar);
        }
        
        return $str;
    }
    
    private function getCols($ar) {
        $str = '';
        foreach( $ar as $col ) {
            $str .= "<col" . ( !empty($col['span'])? " span=\"{$col['span']}\"": '' ) .
                (!empty($col['klass'])? " class=\"{$col['klass']}\"": '') . 
                $this->addAttribs( $col['atts'] ) . ( $this->xhtml? " />": ">" );
        }
        return $str;
    }
    
    private function getSection($sec, $tag) {
        $klass = !empty($sec['klass'])? " class=\"{$sec['klass']}\"": '';
        $atts = !empty($sec['atts'])? $this->addAttribs( $sec['atts'] ): '';
        
        $str = "<$tag" . $klass . $atts . ">\n";
        
        foreach( $sec['rows'] as $row ) {
            $str .= ( !empty( $row['klass'] ) ? "  <tr class=\"{$row['klass']}\"": "  <tr" ) . 
                    $this->addAttribs( $row['atts'] ) . ">\n" . 
                    $this->getRowCells( $row['cells'] ) . "  </tr>\n";
        }
        
        $str .= "</$tag>\n";
        
        return $str;
    }
    
}

?>