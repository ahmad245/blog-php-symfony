
/**
 * @ORM\Entity()
 * @Vich\Uploadable()
 * @ApiResource(
 *     attributes={
 *         "order"={"id": "ASC"},
 *         "formats"={"json", "jsonld", "form"={"multipart/form-data"}}
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={
 *             "method"="POST",
 *             "path"="/images",
 *             "controller"=UploadImageAction::class,
 *             "defaults"={"_api_receive"=false},
 *             "deserialize"=false,
 *             "openapi_context"={
 *                 "requestBody"={
 *                     "content"={
 *                         "multipart/form-data"={
 *                             "schema"={
 *                                 "type"="object",
 *                                 "properties"={
 *                                     "file"={
 *                                         "type"="string",
 *                                         "format"="binary"
 *                                     }
 *                                 }
 *                             }
 *                         }
 *                     }
 *                 }
 *             }
 *         }
 *     },
 *     itemOperations={
 *         "get",
 *         "delete"={
 *             "access_control"="is_granted('ROLE_WRITER')"
 *         }
 *     },
 *   denormalizationContext={"groups"={"post"}},
 *   normalizationContext={
 *               "groups"={"get"}
 *           }
 * )
 */
class Image
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Vich\UploadableField(mapping="images", fileNameProperty="url")
     * @Assert\NotNull()
     */
    private $file;

    /**
     * @ORM\Column(nullable=true)
     * @Groups({"get-post-with-author"})
     */
    private $url;

    public function getId()
    {
        return $this->id;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function setFile($file): void
    {
        $this->file = $file;
        
    }

    public function getUrl()
    {
        return '/images/' . $this->url;
    }

    public function setUrl($url): void
    {
       
        $this->url = $url;
    }

    public function __toString()
    {
        return $this->id . ':' . $this->url;
    }
}
