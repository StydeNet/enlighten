<?php

namespace Styde\Enlighten\Providers;

use Styde\Enlighten\View\Components\AppLayoutComponent;
use Styde\Enlighten\View\Components\BreadcrumbsComponent;
use Styde\Enlighten\View\Components\CodeExampleComponent;
use Styde\Enlighten\View\Components\DynamicTabsComponent;
use Styde\Enlighten\View\Components\EditButtonComponent;
use Styde\Enlighten\View\Components\ExampleBreadcrumbs;
use Styde\Enlighten\View\Components\ExampleTabsComponent;
use Styde\Enlighten\View\Components\ExceptionInfoComponent;
use Styde\Enlighten\View\Components\GroupBreadcrumbs;
use Styde\Enlighten\View\Components\HtmlResponseComponent;
use Styde\Enlighten\View\Components\KeyValueComponent;
use Styde\Enlighten\View\Components\RequestInfoComponent;
use Styde\Enlighten\View\Components\RequestInputTableComponent;
use Styde\Enlighten\View\Components\ResponseInfoComponent;
use Styde\Enlighten\View\Components\RouteParametersTableComponent;
use Styde\Enlighten\View\Components\SearchBoxComponent;
use Styde\Enlighten\View\Components\SearchBoxStaticComponent;
use Styde\Enlighten\View\Components\StatsBadgeComponent;
use Styde\Enlighten\View\Components\StatusBadgeComponent;

trait RegistersViewComponents
{
    protected function registerViewComponents(): void
    {
        $this->loadViewComponentsAs('enlighten', [
            'status-badge' => StatusBadgeComponent::class,
            'response-info' => ResponseInfoComponent::class,
            'request-info' => RequestInfoComponent::class,
            'stats-badge' => StatsBadgeComponent::class,
            'html-response' => HtmlResponseComponent::class,
            'key-value' => KeyValueComponent::class,
            'app-layout' => AppLayoutComponent::class,
            'route-parameters-table' => RouteParametersTableComponent::class,
            'request-input-table' => RequestInputTableComponent::class,
            'dynamic-tabs' => DynamicTabsComponent::class,
            'exception-info' => ExceptionInfoComponent::class,
            'edit-button' => EditButtonComponent::class,
            'breadcrumbs' => BreadcrumbsComponent::class,
            'search-box-static' => SearchBoxStaticComponent::class,
            'search-box' => SearchBoxComponent::class,
            // Group
            'code-example' => CodeExampleComponent::class,
            'content-table' => 'enlighten::components.content-table',
            'response-preview' => 'enlighten::components.response-preview',
            // Layout components
            'info-panel' => 'enlighten::components.info-panel',
            'scroll-to-top' => 'enlighten::components.scroll-to-top',
            'pre' => 'enlighten::components.pre',
            'main-layout' => 'enlighten::layout.main',
            'area-module-panel' => 'enlighten::components.area-module-panel',
            'queries-info' => 'enlighten::components.queries-info',
            'iframe' => 'enlighten::components.iframe',
            'widget' => 'enlighten::components.widget',
            'expansible-section' => 'enlighten::components.expansible-section',
            'svg-logo' => 'enlighten::components.svg-logo',
            'runs-table' => 'enlighten::components.runs-table',
            'panel-title' => 'enlighten::components.panel-title',
            'example-snippets' => 'enlighten::components.example-snippets',
            'example-tabs' => ExampleTabsComponent::class,
            'example-breadcrumbs' => ExampleBreadcrumbs::class,
            'group-breadcrumbs' => GroupBreadcrumbs::class
        ]);
    }
}
