<?php
class Segment_model extends CI_Model{
    
    public $dictionary_array = array();
    function __construct()
    {
         parent::__construct();
        $this->load->helper('file');
        $dictionary_file = "dictionary.txt";
        $openfile = fopen($dictionary_file, 'r');
        if ($openfile) {
        while (!feof($openfile)) 
        {
            $text = fgets($openfile,4096);
            $this->dictionary_array[crc32(trim($text))]= trim($text);
        }
       fclose($openfile);      

        }
   
    }

 
    public function get_segment($content)
    {
            $time_start   = microtime(true);
            $array_words  = $this->get_segment_array($content);
            $time_end     = microtime(true);
            $time         = round($time_end - $time_start,4);
            $array_words_counted = array_count_values($array_words);
            $data = array('sentence'=>$content,'count'=>count($array_words),'word'=> $array_words,'time'=>$time);    
         return $data;     
    }
    
    public function strsplit($string) {
        $split_length = 1;
        preg_match_all('`.`u', $string, $arr);
        $arr = array_chunk($arr[0], $split_length);
        $arr = array_map('implode', $arr);
        return $arr;
    }
    public function uni_strsplit($string, $split_length=1) {
        preg_match_all('`.`u', $string, $arr);
        $arr = array_chunk($arr[0], $split_length);
        $arr = array_map('implode', $arr);
        return $arr;
    }
   
    
    public function get_segment_array( $input_string)
    {
       if($input_string == '')
       {
           return $segmented_result = array();
       }
        //replace these signs with space//
        $input_string = str_replace(array('\'', '‘', '’', '“', '”', '"', '-','_','–', '/', '(', ')', '{', '}', '...', '..', '…', '', ',', ':', '|', '\\'), ' ',$input_string);
    
        $input_string = str_replace(array("\r", "\r\n", "\n"), ' ',$input_string);
        // clear duplicate //
        $input_string = $this->clear_duplicated($input_string);
        
        //cut the pharse into array//
        $string_exploded = explode(' ',$input_string);
        
         foreach ($string_exploded as $string_exploded_row)
         {
            $string_reverse_array = array_reverse($this->strsplit(trim($string_exploded_row)));
            $array_result = $this->segment_by_dictionary_reverse($string_reverse_array);
            foreach ($array_result as $each_result)
            {
                if (trim($each_result) != '')
                {
                     $segmented_result[] = trim($each_result);
                }
            }
        }
        $tmp_result = array();
       foreach ($segmented_result as $result_row)
       {
            if (mb_strlen($result_row) > 10)
            {
                $current_string_array = $this->strsplit(trim($result_row));
                $current_array_result = $this->segment_by_dictionary($current_string_array);

                foreach ($current_array_result as $current_result_row)
                {
                    $tmp_result[] = trim($current_result_row);
                }
            }
           else 
           {
                $tmp_result[] = $result_row;
           }
                
       
       }
        $segmented_result = $tmp_result;
        return $segmented_result;
    }
    
    
     private function segment_by_dictionary($input_array)
     {
        $result_array = array();
        $tmp_string = '';

        $pointer = 0;
        $length_of_string = count($input_array)-1;

        while ($pointer <= $length_of_string)
        {
            $tmp_string .= $input_array[$pointer];
            if (isset($this->dictionary_array[crc32($tmp_string)]))
            { //found in Dict //
                $dup_array = array();
                $dup_array[] = array(
                    'title' => $tmp_string,
                    'mark' => $pointer + 1);
                $count_more = 0;
                $more_tmp = $tmp_string;

                for ($i = $pointer + 1; $i <= $length_of_string; $i++)
                {
                    $more_tmp .= $input_array[$i];
                    if (isset($this->dictionary_array[crc32($more_tmp)]))
                    {
                        $dup_array[] = array(
                            'title' => $more_tmp,
                            'mark' => $i + 1 );
                    }
                    $count_more++;
                }
                if (count($dup_array) > 0)
                {
                    $result_array[] = $dup_array[count($dup_array) - 1]['title'];
                    $pointer = $dup_array[count($dup_array) - 1]['mark'];

                }
                $dup_array = array();
                $tmp_string = '';
                continue;
            }
            $pointer++;
        }

        if ($tmp_string != '')
        { 
            $result_array[] = $tmp_string;
        }

        if (count($result_array) == 0)
        {
            return array(implode($input_array));
        }
        return $result_array;
    }
    
      private function segment_by_dictionary_reverse($input_array)
      {
        $result_array = array();
        $tmp_string = '';
        $pointer = 0;
        $length_of_string = count($input_array)-1;

        while ($pointer <= $length_of_string)
        {
            $tmp_string = $input_array[$pointer] . $tmp_string;

            if (isset($this->dictionary_array[crc32($tmp_string)]))
            { 
                $dup_array = array();
                $dup_array[] = array(
                    'title' => $tmp_string,
                    'mark' => $pointer + 1);
                $count_more = 0;
                $more_tmp = $tmp_string;

                for ($i = $pointer + 1; $i <= $length_of_string; $i++)
                {
                    $more_tmp = $input_array[$i] . $more_tmp;
                    if (isset($this->dictionary_array[crc32($more_tmp)]))
                    {
                        $dup_array[] = array(
                            'title' => $more_tmp,
                            'mark' => $i + 1  );
                    }
                    $count_more++;
                }
                if (count($dup_array) > 0)
                {
                    $result_array[] = $dup_array[count($dup_array) - 1]['title'];
                    $pointer = $dup_array[count($dup_array) - 1]['mark'];

                } 
         
                $dup_array = array();
                $tmp_string = '';
                continue;
            }
            $pointer++;
        }
        if ($tmp_string != '')
        { 
            $result_array[] = $tmp_string;
        }

        if (count($result_array) == 0)
        {
            return array(implode(array_reverse($input_array)));
        }
          
        return array_reverse($result_array);
    }
     private function clear_duplicated($string)
     {
        $input_string_split = $this->uni_strsplit($string);
        $previous_char = '';
        $previous_string = '';
        $dup_list_array = array();
        $dup_list_array_replace = array();
        foreach ($input_string_split as $current_char) {
            if ($previous_char == $current_char) {
                $previous_char = $current_char;
                $previous_string .= $current_char;
            } else {
                if (mb_strlen($previous_string) > 3) {
                    $dup_list_array[] = $previous_string;
                    $dup_list_array_replace[] = $current_char;
                    $string = str_replace($previous_string, $previous_char, $string);
                }
                $previous_char = $current_char;
                $previous_string = $current_char;
            }
        }
        if (mb_strlen($previous_string) > 3) {
            $dup_list_array[] = $previous_string;
            $dup_list_array_replace = $current_char;
        }
        return str_replace($dup_list_array, $dup_list_array_replace, $string);
    }
}

