<?php

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Extension\SandboxExtension;
use Twig\Markup;
use Twig\Sandbox\SecurityError;
use Twig\Sandbox\SecurityNotAllowedTagError;
use Twig\Sandbox\SecurityNotAllowedFilterError;
use Twig\Sandbox\SecurityNotAllowedFunctionError;
use Twig\Source;
use Twig\Template;

/* database/structure/overhead.twig */
class __TwigTemplate_1b1679e62054f90f11fb5d4140e81909fdb91ffcc7d63ec0854f0315114fe7aa extends Template
{
    private $source;
    private $macros = [];

    public function __construct(Environment $env)
    {
        parent::__construct($env);

        $this->source = $this->getSourceContext();

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $macros = $this->macros;
        // line 1
        echo "<a href=\"";
        echo PhpMyAdmin\Url::getFromRoute("/table/structure", ($context["table_url_params"] ?? null));
        echo "#showusage\" id=\"overhead\">
  <span>";
        // line 2
        echo twig_escape_filter($this->env, ($context["formatted_overhead"] ?? null), "html", null, true);
        echo "</span>&nbsp;
  <span class=\"unit\">";
        // line 3
        echo twig_escape_filter($this->env, ($context["overhead_unit"] ?? null), "html", null, true);
        echo "</span>
</a>
";
    }

    public function getTemplateName()
    {
        return "database/structure/overhead.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  46 => 3,  42 => 2,  37 => 1,);
    }

    public function getSourceContext()
    {
        return new Source("", "database/structure/overhead.twig", "/home/bubbledotie/public_html/templates/database/structure/overhead.twig");
    }
}
