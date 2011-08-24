### Constants:

Xml:

    <?xml version="1.0" encoding="UTF-8"?>

    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    >

        <parameters>
            <parameter key="global.constant.value" type="constant">GLOBAL_CONSTANT</parameter>
            <parameter key="my_class.constant.value" type="constant">My_Class::CONSTANT_NAME</parameter>
        </parameters>
    </container>
    