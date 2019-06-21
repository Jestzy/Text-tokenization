<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
    <head>
        <title>ระบบตัดคำด้วย PHP</title>
	    <meta charset="utf-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	    <link  rel="stylesheet" type="text/css"  href="<?php echo base_url(); ?>assets/css/custom_style.css">
	    <link rel="shortcut icon"  type=”image/x-icon” href="../../assets/img/favicon.ico"/>
    </head>
<body>
    <div class='container'>
        <div class="jumbotron transparent text-center">
              <h1 class="display-4 tracking-in-expand">ระบบตัดคำภาษาไทยด้วย PHP</h1>
              <p class="lead">This is a simple system for tokenizing thai sentences into a word using PHP.</p>
              <hr class="my-4">
        </div>
        <div class='row form'>
            <div class='col-sm'>
                <form method='post'>
                    <div>
                        <label>ใส่ประโยคที่ต้องการตัดคำ :</label>
                        <?php
                            $time = '';
                            $text = '';
                            if(isset($response['word'])) {
                                $time = $response['time'];
                                $text = $response['sentence'];
                                //Process further
                            }
                        ?>
                        <textarea type="text"  class='form-control'  value="" name='txt_sentence' style="margin-top: 0px; margin-bottom: 0px; height: 243px;" required><?php echo $text ?></textarea>
                    </div>
                    &nbsp;
                    <div class='text-center'>
                        <button class="btn btn-light" type="submit" name='submit'  value='submit'>ตกลง</button>
                    </div>
                </form>
            </div>
            <div class='col-sm'>
                <?php
                    if(isset($response)){
                        echo "<b>คำที่ตัดได้ :</b><br/>";
                        echo implode(' | ', $response['word']);
                        echo "<hr>";
                        echo "<p>เวลาในการคำนวณ : $time วินาที</p><br/>";
                    }
                ?>
                <br/>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer class="footer mt-auto py-3 ">
        <!-- Copyright -->
        <div class=" text-center">
            © 2019 Copyright: Jatsada Jongrat
        </div>
    </footer>
    
    
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    
</body>