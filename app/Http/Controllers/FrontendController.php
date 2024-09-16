<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\FigmaDesign;
use App\Models\Plans\Plan;
use App\Models\Team;
use App\Notifications\SendContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class FrontendController extends Controller
{
    public function Index()
    {
        list(
            $basicPlan,
            $standardPlan,
            $premiumPlan,
            $basicPlanYearly,
            $standardPlanYearly,
            $premiumPlanYearly
            ) = $this->getPlans();
        /** @var Plan[] $plans */
        $allPlans = [
            'basicPlan' => $basicPlan,
            'standardPlan' => $standardPlan,
            'premiumPlan' => $premiumPlan,
            'basicPlanYearly' => $basicPlanYearly,
            'standardPlanYearly' => $standardPlanYearly,
            'premiumPlanYearly' => $premiumPlanYearly
        ];
        $plans = collect($allPlans);
        return view('frontend.home.index', compact('plans'));
    }

    public function Pricing()
    {
        list(
            $basicPlan,
            $standardPlan,
            $premiumPlan,
            $basicPlanYearly,
            $standardPlanYearly,
            $premiumPlanYearly
            ) = $this->getPlans();

        /** @var Plan[] $plans */
        $plans = [
            'basicPlan' => $basicPlan,
            'standardPlan' => $standardPlan,
            'premiumPlan' => $premiumPlan,
            'basicPlanYearly' => $basicPlanYearly,
            'standardPlanYearly' => $standardPlanYearly,
            'premiumPlanYearly' => $premiumPlanYearly
        ];
        return view('frontend.pricing.index', [
            'plans' => $plans,
        ]);

    }

    public function Blog()
    {
        return view('frontend.blog.index');
    }

    public function BlogDetails()
    {
        return view('frontend.blog.detail');
    }

    public function AiNotification()
    {
        return view('frontend.features.AI_notification.index');
    }

    //ArFeature
    public function ArFeature()
    {
        return view('frontend.features.AR_Features.index');
    }
    public function DesignOption()
    {
        return view('frontend.features.Design_option.index');
    }

    public function overview()
    {
        return view('frontend.grid.index');
    }
    public function detail()
    {
        return view('frontend.grid.detail');
    }
    public function LiveSelling()
    {
        return view('frontend.features.Live_selling.index');
    }

    public function SplashScreen()
    {
        return view('frontend.features.Splash_screen.index');
    }

    //Localization
    public function Localization()
    {
        return view('frontend.features.Localization.index');
    }

    //About
    public function About()
    {
        $teams = Team::query()->get();
        return view('frontend.about.index',compact('teams'));
    }

    //ContactUs
    public function ContactUs()
    {
        return view('frontend.contact_us.index');
    }
    //ContactUsForm
    public function ContactUsForm(Request $request)
    {
        $contact = new Contact();
        $contact->name = $request->input('name');
        $contact->surname = $request->input('surname');
        $contact->phone = $request->input('phone');
        $contact->email = $request->input('email');
        $contact->message = $request->input('message');
        $contact->save();
        Notification::route('mail', 'faizan055ali@gmail.com')
            ->notify(new SendContactMail($contact));
        return redirect()->back()->with('success', 'Your Message has been sent successfully');


    }

    public function Figma(){
        $designs = FigmaDesign::query()->get();
        return view('frontend.figma',compact('designs'));
    }

    /**
     * @return array
     */
    public function getPlans(): array
    {
        $basicPlan = Plan::where('name', 'Basic')->first();
        $standardPlan = Plan::where('name', 'Standard')->first();
        $premiumPlan = Plan::where('name', 'Advanced')->first();

        $basicPlanYearly = Plan::whereName('Basic')->whereDuration('365')->first();
        $standardPlanYearly = Plan::whereName('Standard')->whereDuration('365')->first();
        $premiumPlanYearly = Plan::whereName('Advanced')->whereDuration('365')->first();
        return array($basicPlan, $standardPlan, $premiumPlan, $basicPlanYearly, $standardPlanYearly, $premiumPlanYearly);
    }
    public function PrivacyPolicy()
    {
        return view('frontend.privacy-policy');
    }


}
