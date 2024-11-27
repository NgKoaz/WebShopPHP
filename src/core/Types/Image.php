<?php

namespace App\core\Types;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class Image
{
    private static string $IMAGE_STORAGE_RELATIVE_PATH = "/public/upload/images";

    private static string $IMAGE_STORAGE_PATH = ROOT_DIR . "/public/upload/images";

    private static array $ALLOW_EXTENSION = ["apng", "png", "avif", "svg", "webp", "jpg", "jpeg"];

    private string $filename = "";

    private string $shortname = "";

    private string $extension = "";

    private string $relativePath = "";

    private string $absolutePath = "";

    private array $anotherVersion = [];

    public function __construct(?array $file = null)
    {
        if ($file === null || count($file) <= 0) return;
        $shortname = uniqid(true) . time();
        $nameParts = explode(".", $file["name"]);
        if (count($nameParts) !== 2) return;
        $extension = strtolower($nameParts[1]);
        $filename = $shortname . "." . $extension;

        if (!in_array($extension, Image::$ALLOW_EXTENSION)) return;

        $absolutePath = Image::$IMAGE_STORAGE_PATH . "/" . $filename;
        $relativePath = Image::$IMAGE_STORAGE_RELATIVE_PATH . "/" . $filename;

        move_uploaded_file($file["tmp_name"], $absolutePath);
        $this->filename = $filename;
        $this->shortname = $shortname;
        $this->extension = $extension;
        $this->absolutePath = $absolutePath;
        $this->relativePath = $relativePath;
    }

    public function getAbsolutePath(): string
    {
        return $this->absolutePath;
    }

    public function getRelativePath(): string
    {
        return $this->relativePath;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function createAnotherVersion(int $width, int $height): string
    {
        if (strlen($this->absolutePath) <= 0) return "";

        $newFilename = uniqid(true) . time() . "." . $this->extension;
        $newAbsolutePath = Image::$IMAGE_STORAGE_PATH . "/" . $newFilename;
        $newRelativePath = Image::$IMAGE_STORAGE_RELATIVE_PATH . "/" . $newFilename;

        $manager = new ImageManager(new Driver());
        $image = $manager->read($this->absolutePath);

        $image->scale($width, $height);
        $image->toPng()->save($newAbsolutePath);

        $this->anotherVersion[] = $newAbsolutePath;
        return $newRelativePath;
    }

    public function removeImages(): void
    {
        if (file_exists($this->absolutePath)) {
            unlink($this->absolutePath);
            foreach ($this->anotherVersion as $path) {
                unlink($path);
            }
        }
    }
}
