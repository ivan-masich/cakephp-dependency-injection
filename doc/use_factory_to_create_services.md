### Basic example:

Xml:

    <parameters>
        <!-- ... -->
        <parameter key="newsletter_manager.class">Acme\HelloBundle\Newsletter\NewsletterManager</parameter>
        <parameter key="newsletter_factory.class">Acme\HelloBundle\Newsletter\NewsletterFactory</parameter>
    </parameters>

    <services>
        <service id="newsletter_manager"
                 class="%newsletter_manager.class%"
                 factory-class="%newsletter_factory.class%"
                 factory-method="get"
        />
    </services>

Yaml:

    parameters:
        # ...
        newsletter_manager.class: Acme\HelloBundle\Newsletter\NewsletterManager
        newsletter_factory.class: Acme\HelloBundle\Newsletter\NewsletterFactory
    services:
        newsletter_manager:
            class:          %newsletter_manager.class%
            factory_class:  %newsletter_factory.class%
            factory_method: get

### Factory object as another service:

Xml:

    <parameters>
        <!-- ... -->
        <parameter key="newsletter_manager.class">Acme\HelloBundle\Newsletter\NewsletterManager</parameter>
        <parameter key="newsletter_factory.class">Acme\HelloBundle\Newsletter\NewsletterFactory</parameter>
    </parameters>

    <services>
        <service id="newsletter_factory" class="%newsletter_factory.class%"/>
        <service id="newsletter_manager"
                 class="%newsletter_manager.class%"
                 factory-service="newsletter_factory"
                 factory-method="get"
        />
    </services>

Yaml:

    parameters:
        # ...
        newsletter_manager.class: Acme\HelloBundle\Newsletter\NewsletterManager
        newsletter_factory.class: Acme\HelloBundle\Newsletter\NewsletterFactory
    services:
        newsletter_factory:
            class:            %newsletter_factory.class%
        newsletter_manager:
            class:            %newsletter_manager.class%
            factory_service:  newsletter_factory
            factory_method:   get

### Passing Arguments to the Factory Method

Xml:

    <parameters>
        <!-- ... -->
        <parameter key="newsletter_manager.class">Acme\HelloBundle\Newsletter\NewsletterManager</parameter>
        <parameter key="newsletter_factory.class">Acme\HelloBundle\Newsletter\NewsletterFactory</parameter>
    </parameters>

    <services>
        <service id="newsletter_factory" class="%newsletter_factory.class%"/>
        <service id="newsletter_manager"
                 class="%newsletter_manager.class%"
                 factory-service="newsletter_factory"
                 factory-method="get"
        >
            <argument type="service" id="templating" />
        </service>
    </services>

Yaml:

    parameters:
        # ...
        newsletter_manager.class: Acme\HelloBundle\Newsletter\NewsletterManager
        newsletter_factory.class: Acme\HelloBundle\Newsletter\NewsletterFactory
    services:
        newsletter_factory:
            class:            %newsletter_factory.class%
        newsletter_manager:
            class:            %newsletter_manager.class%
            factory_service:  newsletter_factory
            factory_method:   get
            arguments:
                -             @templating
