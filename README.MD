# ezFieldIterator

## Filter "Content Class -Fields-" with specific value type instance

### Instructions

Create new Filters().
```
 $fieldHelper = new Filters();
```

Set the Content need to filter fields
- support only  "eZ\Publish\API\Repository\Values\Content\Content"
- Single $content or Array [$content, $content, $content]
```
$fieldHelper->setContent($content);
```
Choose the filter "value class" instance
- Choose single or multiple instance of class for filtering the content
```
use eZ\Publish\Core\FieldType\Image\Value as ImageValue;
use eZ\Publish\Core\FieldType\RichText\Value as RichTextValue;
use eZ\Publish\Core\FieldType\TextBlock\Value as TextBlockValue;
use eZ\Publish\Core\FieldType\TextLine\Value as TextLineValue;
```
- Single Value::class or [ValueA::class, ValueB::class, ValueC::class]
```
$fieldHelper->setClass(ImageValue::class);
```

- Field name from get value (Required)
```
$fieldHelper->setValueField('uri');
```

### Example
```
use unrealmanu\ezFieldIterator\Filters as FieldHelper;

/**
* @return string|null
*/
function myFilter():?string
{
    $fieldHelper = new FieldHelper();
    $fieldHelper->setContent($content);
    $fieldHelper->setClass(ImageValue::class);
    $fieldHelper->setValueField('uri');

    return $fieldHelper->getFirstChildren();
    //return $fieldHelper->getChildren();
}
```

### Real World application
- Get all images of "contents array" for image-sitemap
- Get first image/text of "contents array" for search-indexing
- Get all text field of Content for generate list of Tag/Description etc
- Use your creativity :)
