<?php

namespace Modules\Slider\View\Components\Slider;

use Illuminate\View\Component;

class Owl extends Component
{

  public $id;
  public $layout;
  public $slider;
  public $view;
  public $height;
  public $margin;
  public $loopOwl; //renamed because the $loop is reserved into de blade @foreach
  public $dots;
  public $dotsPosition;
  public $dotsStyle;
  public $dotsStyleColor;
  public $nav;
  public $navText;
  public $autoplay;
  public $autoplayHoverPause;
  public $autoplayTimeout;
  public $containerFluid;
  public $imgObjectFit;
  public $responsive;
  public $responsiveClass;
  public $orderClasses;
  public $editLink;
  public $tooltipEditLink;
  public $withViewMoreButton;
  public $stagePadding;
  public $container;
  public $slides;
  public $itemComponentAttributes;
  public $itemComponentNamespace;
  public $itemComponent;
  public $navPosition;
  public $mouseDrag;
  public $touchDrag;
  public $navLateralLeftRight;
  public $navLateralTop;
  public $dotsBottom;
  public $isMobile;

  /**
   * Create a new component instance.
   *
   * @return void
   */
  public function __construct($id, $layout = 'slider-owl-layout-1', $height = '500px', $autoplay = true, $margin = 0,
                              $autoplayHoverPause = true, $loop = true, $dots = true, $dotsPosition = 'center',
                              $dotsStyle = 'line', $nav = true, $navText = "", $autoplayTimeout = 10000,
                              $imgObjectFit = "cover", $responsiveClass = false, $responsive = null, $orderClasses = [],
                              $withViewMoreButton = true, $container = "container", $stagePadding = 0, $view = null,
                              $itemComponentAttributes = [], $itemComponentNamespace = null, $itemComponent = null,
                              $navPosition = 'lateral', $mouseDrag = true, $touchDrag = true, $navLateralTop = 50,
                              $navLateralLeftRight = '15px', $dotsStyleColor = '#fff', $dotsBottom = 0
  )
  {
    $this->id = $id;
    $this->layout = $layout ?? 'slider-owl-layout-1';
    $this->height = $height ?? '500px';
    $this->margin = $margin ?? 0;
    $this->dots = $dots ?? true;
    $this->dotsPosition = $dotsPosition ?? 'center';
    $this->dotsStyle = $dotsStyle ?? 'line';
    $this->dotsStyleColor = $dotsStyleColor;
    $this->nav = $nav ?? true;
    $this->navText = json_encode($navText);
    $this->loopOwl = $loop ?? true;
    $this->autoplay = $autoplay ?? true;
    $this->autoplayHoverPause = $autoplayHoverPause ?? true;
    $this->autoplayTimeout = $autoplayTimeout ?? 10000;
    $this->imgObjectFit = $imgObjectFit ?? "cover";
    $this->responsive = json_encode($responsive ?? [0 => ["items" => 1]]);
    $this->responsiveClass = $responsiveClass;
    $this->orderClasses = !empty($orderClasses) ? $orderClasses : ["photo" => "order-0", "content" => "order-1"];
    list($this->editLink, $this->tooltipEditLink) = getEditLink('Modules\Slider\Repositories\SlideRepository');
    $this->withViewMoreButton = $withViewMoreButton;
    $this->stagePadding = $stagePadding;
    $this->container = $container;
    $this->view = $view ?? "slider::frontend.components.slider.owl.layouts.{$this->layout}.index";
    $this->getItem();
    $this->itemComponent = $itemComponent ?? "isite::item-list";
    $this->itemComponentNamespace = $itemComponentNamespace ?? "Modules\Isite\View\Components\ItemList";
    $this->itemComponentAttributes = count($itemComponentAttributes) ? $itemComponentAttributes : config('asgard.slider.config.indexItemListAttributes');
    $this->navPosition = $navPosition ?? 'lateral';
    $this->mouseDrag = $mouseDrag;
    $this->touchDrag = $touchDrag;
    $this->navLateralLeftRight = $navLateralLeftRight;
    $this->navLateralTop = explode(",", $navLateralTop);
    $this->dotsBottom = $dotsBottom;
    $this->isMobile = isMobileDevice();
  }


  public function getItem()
  {
    $params = [
      'filter' => [
        'field' => 'system_name',
      ]
    ];

    $this->slider = app('Modules\\Slider\\Repositories\\SliderRepository')->getItem($this->id, json_decode(json_encode($params)));
    if (!$this->slider) {
      $params['filter']['field'] = 'id';
      $this->slider = app('Modules\\Slider\\Repositories\\SliderRepository')->getItem($this->id, json_decode(json_encode($params)));

    }

    $params = [
      'filter' => [
        'sliderId' => $this->slider->id ?? null,
      ],
      'include' => ['files', 'translations']
    ];

    $this->slides = app('Modules\\Slider\\Repositories\\SlideRepository')->getItemsBy(json_decode(json_encode($params)));


  }

  /**
   * Get the view / contents that represent the component.
   *
   * @return \Illuminate\Contracts\View\View|string
   */
  public function render()
  {
    if (!isset($this->slider->id))
      return view("slider::frontend.components.slider.owl.invalid-slider");
    return view($this->view);
  }
}