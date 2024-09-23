<?php
declare(strict_types=1);

namespace Pentagonal\Hub\Schema\Whmcs\Definitions;

use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Checkbox;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Group;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Hidden;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Html;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\HtmlTagAttributes;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Input;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Radio;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Select;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Spacer;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Text;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\TextArea;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Title;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Properties\Toggle;
use Pentagonal\Hub\Schema\Whmcs\Definitions\Types\SettingsType;
use Swaggest\JsonSchema\Schema;
use Swaggest\JsonSchema\Structure\ClassStructure;

class Definition extends ClassStructure
{
    public static function setUpProperties($properties, Schema $ownerSchema)
    {
        $ownerSchema->settings = SettingsType::schema();
//        $ownerSchema->tagName = TagNameType::schema();
        $ownerSchema->htmlTagAttributes = HtmlTagAttributes::schema();
        $ownerSchema->checkBox = Checkbox::schema();
        $ownerSchema->toggle = Toggle::schema();
        $ownerSchema->radio = Radio::schema();
        $ownerSchema->html = Html::schema();
        $ownerSchema->title = Title::schema();
        $ownerSchema->group = Group::schema();
        $ownerSchema->spacer = Spacer::schema();
        $ownerSchema->textarea = TextArea::schema();
        $ownerSchema->text = Text::schema();
        $ownerSchema->hidden = Hidden::schema();
        $ownerSchema->select = Select::schema();
        $ownerSchema->input = Input::schema();
    }
}
