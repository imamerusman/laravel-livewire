<?php

namespace App\Livewire;

use Livewire\Component;

class IframeOfFigma extends Component
{
    public string $src = '';

    public function render()
    {
        return view('livewire.ifram-of-figma');
    }

    public function placeholder()
    {
        return <<<'HTML'

        <div>
        <style>
            .custom-loader {
                border: 16px solid #f3f3f3;
                border-radius: 50%;
                border-top: 16px solid #F4B41A;
                width: 120px;
                height: 120px;
                -webkit-animation: spin 2s linear infinite; /* Safari */
                animation: spin 2s linear infinite;
                margin: 0 auto;
                margin-top: 20%;
            }

            /* Safari */
            @-webkit-keyframes spin {
                0% { -webkit-transform: rotate(0deg); }
                100% { -webkit-transform: rotate(360deg); }
            }

            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
        </style>

            <!-- Loading spinner... -->
           <div class="custom-loader"></div>
        </div>
        HTML;
    }
}
