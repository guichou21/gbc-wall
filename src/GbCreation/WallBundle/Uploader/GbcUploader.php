<?php
// Mon Service pour gérer les Spam

namespace GbCreation\WallBundle\Uploader; 

class GbcUploader
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function update(Picture $picture, $randomize = true)
    {
        $file = $picture->getLocation();

        if (!$file instanceof File) {
            throw new \InvalidArgumentException(
                    'There is no file to upload!'
            );
        }
        $fileName = $this->generateFilename($file, $randomize);

        $file->move($this->directory, $fileName);

        $picture->setLocation($fileName);
    }

    public function upload(ImageInterface $picture, $randomize = true)
    {
        $file = $picture->getLocation();
        if (!$file instanceof UploadedFile) {
            throw new \InvalidArgumentException(
                    'There is no file to upload!'
            );
        }
        $fileName = $this->generateFilename($file, $randomize);

        $file->move($this->directory, $fileName);
        $picture->setLocation($fileName);
    }

    private function generateFilename(File $file, $randomize = true)
    {
        if ($randomize) {
            $filename = sprintf('%s.%s'
                    , md5(uniqid($file, true)), $file->guessExtension());

        } else {
            $filename = sprintf('%s.%s', $file->getBasename(), $file->guessExtension());
        }

        return $filename;
    }

}