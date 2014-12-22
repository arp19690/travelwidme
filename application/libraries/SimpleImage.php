<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class SimpleImage
    {

        var $image;
        var $image_type;

        function load($filename)
        {

            $image_info = @getimagesize($filename);
            $this->image_type = $image_info[2];
            if ($this->image_type == IMAGETYPE_JPEG)
            {

                $this->image = imagecreatefromjpeg($filename);
            }
            elseif ($this->image_type == IMAGETYPE_GIF)
            {

                $this->image = imagecreatefromgif($filename);
            }
            elseif ($this->image_type == IMAGETYPE_PNG)
            {

                $this->image = imagecreatefrompng($filename);
            }
        }

        function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 75, $permissions = null)
        {

            if ($image_type == IMAGETYPE_JPEG)
            {
                @imagejpeg($this->image, $filename, $compression);
            }
            elseif ($image_type == IMAGETYPE_GIF)
            {

                imagegif($this->image, $filename);
            }
            elseif ($image_type == IMAGETYPE_PNG)
            {

                imagepng($this->image, $filename);
            }
            if ($permissions != null)
            {

                chmod($filename, $permissions);
            }
        }

        function output($image_type = IMAGETYPE_JPEG)
        {

            if ($image_type == IMAGETYPE_JPEG)
            {
                @imagejpeg($this->image);
            }
            elseif ($image_type == IMAGETYPE_GIF)
            {

                imagegif($this->image);
            }
            elseif ($image_type == IMAGETYPE_PNG)
            {

                imagepng($this->image);
            }
        }

        function getWidth()
        {

            return @imagesx($this->image);
        }

        function getHeight()
        {

            return imagesy($this->image);
        }

        function resizeToHeight($height)
        {

            $ratio = $height / $this->getHeight();
            $width = $this->getWidth() * $ratio;
            $this->resize($width, $height);
        }

        function resizeToWidth($width)
        {
            $ratio = $width / $this->getWidth();
            $height = $this->getheight() * $ratio;
            $this->resize($width, $height);
        }

        function scale($scale)
        {
            $width = $this->getWidth() * $scale / 100;
            $height = $this->getheight() * $scale / 100;
            $this->resize($width, $height);
        }

        function resize($width, $height)
        {
            $new_image = @imagecreatetruecolor($width, $height);
            @imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
            $this->image = $new_image;
        }

        public function uploadImage($source, $destination, $width = 300, $height = NULL)
        {
            $this->load($source);

            if ($height == NULL || empty($height))
            {
                $this->resizeToWidth($width);
            }
            else
            {
                $this->resize($width, $height);
            }

            //save image
            $this->save($destination);
        }

        public function textWatermark($text, $file_path_on_system, $font_size = '12', $font_color='c1c1c1')
        {
            $this->ci = & get_instance();
            $this->ci->load->library('image_lib');
            $config['image_library'] = 'gd2';
            $config['source_image'] = $file_path_on_system;
            $config['wm_text'] = $text;
            $config['wm_type'] = 'text';
            $config['wm_font_path'] = CSS_PATH . "/../fonts/sketchy/sketchy.ttf";
            $config['wm_font_size'] = $font_size;
            $config['wm_font_color'] = $font_color;
            $config['wm_vrt_alignment'] = 'top';
            $config['wm_hor_alignment'] = 'left';
            $config['wm_padding'] = '5';

            $this->ci->image_lib->initialize($config);
            $this->ci->image_lib->watermark();
        }

    }

?>
