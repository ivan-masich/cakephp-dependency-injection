### Service Parameters

Xml:

    <?xml version="1.0" encoding="UTF-8" ?>

    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

        <parameters>
            <parameter key="my_mailer.class">Acme\HelloBundle\Mailer</parameter>
            <parameter key="my_mailer.transport">sendmail</parameter>
        </parameters>

        <services>
            <service id="my_mailer" class="%my_mailer.class%">
                <argument>%my_mailer.transport%</argument>
            </service>
        </services>

    </container>

Yaml:

    parameters:
        my_mailer.class:        Acme\HelloBundle\Mailer
        my_mailer.transport:    sendmail

    services:
        my_mailer:
            class:              %my_mailer.class%
            arguments:          [%my_mailer.transport%]

### Referencing (Injecting) Services

Xml:

    <?xml version="1.0" encoding="UTF-8" ?>

    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

        <parameters>
            <!-- ... -->
            <parameter key="newsletter_manager.class">Acme\HelloBundle\Newsletter\NewsletterManager</parameter>
        </parameters>

        <services>
            <service id="my_mailer" ... >
              <!-- ... -->
            </service>
            <service id="newsletter_manager" class="%newsletter_manager.class%">
                <argument type="service" id="my_mailer"/>
            </service>
        </services>

    </container>

Yaml:

    parameters:
        # ...
        newsletter_manager.class: Acme\HelloBundle\Newsletter\NewsletterManager

    services:
        my_mailer:
            # ...
        newsletter_manager:
            class:     %newsletter_manager.class%
            arguments: [@my_mailer]

### Optional Dependencies: Setter Injection

Xml:

    <?xml version="1.0" encoding="UTF-8" ?>

    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

        <parameters>
            <!-- ... -->
            <parameter key="newsletter_manager.class">Acme\HelloBundle\Newsletter\NewsletterManager</parameter>
        </parameters>

        <services>
            <service id="my_mailer" ... >
              <!-- ... -->
            </service>
            <service id="newsletter_manager" class="%newsletter_manager.class%">
                <call method="setMailer">
                     <argument type="service" id="my_mailer" />
                </call>
            </service>
        </services>

    </container>

Yaml:

    parameters:
        # ...
        newsletter_manager.class: Acme\HelloBundle\Newsletter\NewsletterManager

    services:
        my_mailer:
            # ...
        newsletter_manager:
            class:     %newsletter_manager.class%
            calls:
                - [ setMailer, [ @my_mailer ] ]

### Making References Optional

Xml:

    <?xml version="1.0" encoding="UTF-8" ?>

    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

        <services>
            <service id="my_mailer" ... >
              <!-- ... -->
            </service>
            <service id="newsletter_manager" class="%newsletter_manager.class%">
                <argument type="service" id="my_mailer" on-invalid="ignore" />
            </service>
        </services>

    </container>

Yaml:

    parameters:
        # ...

    services:
        newsletter_manager:
            class:     %newsletter_manager.class%
            arguments: [@?my_mailer]

### Marking Services as public / private
This is common when a service is only defined because it could be used as an argument for another service.

Xml:

    <?xml version="1.0" encoding="UTF-8" ?>

    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

        <service id="foo" class="Acme\HelloBundle\Foo" public="false" />

    </container>

Yaml:

    services:
        foo:
            class: Acme\HelloBundle\Foo
            public: false

### Aliasing

Xml:

    <?xml version="1.0" encoding="UTF-8" ?>

    <container xmlns="http://symfony.com/schema/dic/services"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

        <service id="foo" class="Acme\HelloBundle\Foo"/>

        <service id="bar" alias="foo" />

    </container>

Yaml:

    services:
        foo:
            class: Acme\HelloBundle\Foo
        bar:
            alias: foo
