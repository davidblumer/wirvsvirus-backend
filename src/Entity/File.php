<?php

namespace App\Entity;

use App\Helper\FileName;
use Symfony\Component\HttpFoundation\File\File;
use Knp\DoctrineBehaviors\Model as ORMBehaviors;

class Image
{
    use ORMBehaviors\Blameable\BlameableTrait,
        ORMBehaviors\Timestampable\TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @Vich\UploadableField(mapping="uploaded_image", fileNameProperty="imageName")
     *
     * @var File
     */
    protected $imageFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $imageName;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $imagePath;

    public function getId()
    {
        return $this->id;
    }

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|null $image
     * @return $this
     * @throws \Exception
     */
    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        if ($image) {
            $this->setUpdatedAt(new \DateTime());
        }

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @param string $imageName
     *
     * @return Image
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImageName()
    {
        $name          = $this->imageName;
        $validFileName = FileName::getValidFileName($name);

        if ($validFileName) {
            $name = $validFileName;
        }

        return $name;
    }

    /**
     * @param bool $addIfPathNotAbsolute
     * @return string
     */
    public function getImagePath($addIfPathNotAbsolute = false)
    {
        $imagePath = $this->imagePath;

        if ($addIfPathNotAbsolute && strpos($imagePath, 'http') === false) {
            $imagePath = $addIfPathNotAbsolute . $imagePath;
        }

        return $imagePath;
    }
}