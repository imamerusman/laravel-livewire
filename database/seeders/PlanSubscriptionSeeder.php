<?php

namespace Database\Seeders;

use App\Models\Plans\Feature;
use App\Models\Plans\Plan;
use App\Models\Plans\PlanFeature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSubscriptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*Basic Plan*/

        $basicPlan = Plan::create([
            'name' => 'Basic',
            'description' => 'Basic Information',
            'duration' => 30, // in days
            'price' => 10000, // in cents
            'currency' => 'usd',
        ]);
        $basicPlanYearly = Plan::create([
            'name' => 'Basic',
            'description' => 'Basic Information',
            'duration' => 365, // in days
            'price' => 7500, // in cents
            'currency' => 'usd',
        ]);
        $orders = new Feature([
            'name' => 'orders',
            'code' => 'app_order',
            'description' => 'Upto 100 orders',
            'type' => 'feature',
        ]);
        $appSplashScreen = new Feature([
            'name' => 'Android & iOS Native Apps',
            'code' => 'app.splash-screen',
            'description' => 'Splash Screen',
            'type' => 'feature',
        ]);
        $appHomePage = new Feature([
            'name' => 'Android & iOS Native Apps',
            'code' => 'app.home-page',
            'description' => 'Home Page',
            'type' => 'feature',
        ]);
        $appProductPage = new Feature([
            'name' => 'Android & iOS Native Apps',
            'code' => 'app.product-page',
            'description' => 'Product Page',
            'type' => 'feature',
        ]);
        $appAddToCartPage = new Feature([
            'name' => 'Android & iOS Native Apps',
            'code' => 'app.add_to_cart-page',
            'description' => 'Add to Cart Page',
            'type' => 'feature',
        ]);
        $appCheckoutPage = new Feature([
            'name' => 'Android & iOS Native Apps',
            'code' => 'app.checkout-page',
            'description' => 'Check out Page',
            'type' => 'feature',
        ]);
        $appSignInPage = new Feature([
            'name' => 'Android & iOS Native Apps',
            'code' => 'app.signing-page',
            'description' => 'Sign In Page',
            'type' => 'feature',
        ]);
        $appSignUpPage = new Feature([
            'name' => 'Android & iOS Native Apps',
            'code' => 'app.sign_up-page',
            'description' => 'Sign up Page',
            'type' => 'feature',
        ]);
        $appFilterPage = new Feature([
            'name' => 'Android & iOS Native Apps',
            'code' => 'app.filter-page',
            'description' => 'Filter Page',
            'type' => 'feature',
        ]);
        $appSearchPage = new Feature([
            'name' => 'Android & iOS Native Apps',
            'code' => 'app.search-page',
            'description' => 'Search Page',
            'type' => 'feature',
        ]);

        $instantNotifications = new Feature([
            'name' => 'Notifications',
            'code' => 'notification.instant-notification',
            'description' => 'Instant Notifications',
            'type' => 'feature',
        ]);
        $scheduledNotifications = new Feature([
            'name' => 'Notifications',
            'code' => 'notification.scheduled-notification',
            'description' => 'Scheduled Notifications',
            'type' => 'feature',
        ]);

        $revisions = new Feature([
            'name' => 'Revision',
            'code' => 'revision',
            'description' => '3 Revision',
            'type' => 'limit',
            'limit' => 3,
        ]);

        $notificationAnalytics = new Feature([
            'name' => 'Analytics',
            'code' => 'analytics.notification',
            'description' => 'Notification Analytics',
            'type' => 'feature',
        ]);
        $cartAnalytics = new Feature([
            'name' => 'Analytics',
            'code' => 'analytics.cart',
            'description' => 'Cart Analytics',
            'type' => 'feature',
        ]);
        $checkoutAnalytics = new Feature([
            'name' => 'Analytics',
            'code' => 'analytics.checkout',
            'description' => 'Check out Analytics',
            'type' => 'feature',
        ]);
        $searchProductAnalytics = new Feature([
            'name' => 'Analytics',
            'code' => 'analytics.search-product',
            'description' => 'Most Search Product Analytics',
            'type' => 'feature',
        ]);

        $supportAppifyStore = new Feature([
            'name' => 'support',
            'code' => 'support',
            'description' => '24/7 Support to appify store',
            'type' => 'feature',
        ]);

        $iOSAppPublication = new Feature([
            'name' => 'publication',
            'code' => 'app_publication',
            'description' => 'Google iOS App Publication',
            'type' => 'feature',
        ]);

        $basicPlan->features()->saveMany([
            $orders,
            $appSplashScreen,
            $appHomePage,
            $appProductPage,
            $appAddToCartPage,
            $appCheckoutPage,
            $appSignInPage,
            $appSignUpPage,
            $appFilterPage,
            $appSearchPage,
            $instantNotifications,
            $scheduledNotifications,
            $notificationAnalytics,
            $cartAnalytics,
            $checkoutAnalytics,
            $searchProductAnalytics,
            $supportAppifyStore,
            $iOSAppPublication,
        ]);
        $basicPlanYearly->features()->saveMany([

            $appSplashScreen,
            $appHomePage,
            $appProductPage,
            $appAddToCartPage,
            $appCheckoutPage,
            $appSignInPage,
            $appSignUpPage,
            $appFilterPage,
            $appSearchPage,
            $instantNotifications,
            $scheduledNotifications,
            $notificationAnalytics,
            $cartAnalytics,
            $checkoutAnalytics,
            $searchProductAnalytics,
            $supportAppifyStore,
            $iOSAppPublication,
        ]);


        /*Standard Plan*/


        $standardPlan = Plan::create([
            'name' => 'Standard',
            'description' => 'Standard Information',
            'duration' => 30, // in days
            'price' => 26000, // in cents
            'currency' => 'usd',
        ]);

        $standardPlanYearly = Plan::create([
            'name' => 'Standard',
            'description' => 'Standard Information',
            'duration' => 365, // in days
            'price' => 19500, // in cents
            'currency' => 'usd',
        ]);

        $ordersStandard = new Feature([
            'name' => 'orders',
            'code' => 'app_order',
            'description' => 'Upto 500 orders',
            'type' => 'feature',
        ]);
        $aiTitleNotification = new Feature([
            'name' => 'Notifications',
            'code' => 'notification.title-ai',
            'description' => 'Title AI',
            'type' => 'feature',
        ]);
        $aiDescriptionNotification = new Feature([
            'name' => 'Notifications',
            'code' => 'notification.description-ai',
            'description' => 'Description AI',
            'type' => 'feature',
        ]);
        $cartAbandonedNotification = new Feature([
            'name' => 'Notifications',
            'code' => 'notification.cart-abandoned-notification',
            'description' => 'Cart Abandoned Notification',
            'type' => 'feature',
        ]);
        $stockNotification = new Feature([
            'name' => 'Notifications',
            'code' => 'notification.stock-notification',
            'description' => 'Back-in Stock Notification',
            'type' => 'feature',
        ]);
        $appTerminationNotification = new Feature([
            'name' => 'Notifications',
            'code' => 'notification.app_termination-notification',
            'description' => 'Notification on app Termination',
            'type' => 'feature',
        ]);
        $recommendedProductNotification = new Feature([
            'name' => 'Notifications',
            'code' => 'notification.recommended_product-notification',
            'description' => 'Recommended Product Notification',
            'type' => 'feature',
        ]);
        $historyNotification = new Feature([
            'name' => 'Notifications',
            'code' => 'notification.recommended_product-notification',
            'description' => 'Notification based on History',
            'type' => 'feature',
        ]);

        $sellingProductAnalytics = new Feature([
            'name' => 'Analytics',
            'code' => 'analytics.selling-product',
            'description' => 'Most Selling Product Analytics',
            'type' => 'feature',
        ]);
        $viewedProductAnalytics = new Feature([
            'name' => 'Analytics',
            'code' => 'analytics.view-product',
            'description' => 'Most viewed Product Analytics.',
            'type' => 'feature',
        ]);

        $deepLinking = new Feature([
            'name' => 'Deep Linking',
            'code' => 'deeplink',
            'description' => 'Deep Linking',
            'type' => 'feature',
        ]);

        $facebookLiveSelling = new Feature([
            'name' => 'Live Selling',
            'code' => 'live_selling.facebook',
            'description' => 'Facebook integration',
            'type' => 'feature',
        ]);
        $instagramLiveSelling = new Feature([
            'name' => 'Live Selling',
            'code' => 'live_selling.instagram',
            'description' => 'Instagram integration',
            'type' => 'feature',
        ]);
        $youtubeLiveSelling = new Feature([
            'name' => 'Live Selling',
            'code' => 'live_selling.youtube',
            'description' => 'YouTube integration',
            'type' => 'feature',
        ]);

        $ltrRtlSupport = new Feature([
            'name' => 'LTR/RTL Support',
            'code' => 'ltr_rtl_support',
            'description' => 'RTL / LTR Support',
            'type' => 'feature',
        ]);

        $localization = new Feature([
            'name' => 'Localizations',
            'code' => 'localization',
            'description' => 'Localizations (Multi Language)',
            'type' => 'feature',
        ]);

        $arFeature = new Feature([
            'name' => 'Ar Feature',
            'code' => 'ar_feature',
            'description' => 'AR Feature (Per product included)',
            'type' => 'feature',
        ]);

        $chatCustomerToAdmin = new Feature([
            'name' => 'Chat Support Feature',
            'code' => 'chat.customer_to_admin',
            'description' => 'Customer to admin',
            'type' => 'feature',
        ]);
        $chatAdminToAppify = new Feature([
            'name' => 'Chat Support Feature',
            'code' => 'chat.admin_to_appify',
            'description' => 'Admin to Appify',
            'type' => 'feature',
        ]);

        $standardPlan->features()->saveMany([
            $ordersStandard,
            $appSplashScreen,
            $appHomePage,
            $appProductPage,
            $appAddToCartPage,
            $appCheckoutPage,
            $appSignInPage,
            $appSignUpPage,
            $appFilterPage,
            $appSearchPage,
            $revisions,
            $aiTitleNotification,
            $aiDescriptionNotification,
            $cartAbandonedNotification,
            $stockNotification,
            $appTerminationNotification,
            $recommendedProductNotification,
            $historyNotification,
            $instantNotifications,
            $scheduledNotifications,
            $notificationAnalytics,
            $cartAnalytics,
            $checkoutAnalytics,
            $searchProductAnalytics,
            $sellingProductAnalytics,
            $viewedProductAnalytics,
            /*$deepLinking,*/
            $facebookLiveSelling,
            $instagramLiveSelling,
            $youtubeLiveSelling,
            $ltrRtlSupport,
            $localization,
            /*$arFeature,*/
            $supportAppifyStore,
            $iOSAppPublication,
            $chatCustomerToAdmin,
            $chatAdminToAppify,
        ]);
        $standardPlanYearly->features()->saveMany([
            $appSplashScreen,
            $appHomePage,
            $appProductPage,
            $appAddToCartPage,
            $appCheckoutPage,
            $appSignInPage,
            $appSignUpPage,
            $appFilterPage,
            $appSearchPage,
            $revisions,
            $aiTitleNotification,
            $aiDescriptionNotification,
            $cartAbandonedNotification,
            $stockNotification,
            $appTerminationNotification,
            $recommendedProductNotification,
            $historyNotification,
            $instantNotifications,
            $scheduledNotifications,
            $notificationAnalytics,
            $cartAnalytics,
            $checkoutAnalytics,
            $searchProductAnalytics,
            $sellingProductAnalytics,
            $viewedProductAnalytics,
            /*$deepLinking,*/
            $facebookLiveSelling,
            $instagramLiveSelling,
            $youtubeLiveSelling,
            $ltrRtlSupport,
            $localization,
            /*$arFeature,*/
            $supportAppifyStore,
            $iOSAppPublication,
            $chatCustomerToAdmin,
            $chatAdminToAppify,
        ]);

        /*Advanced Plan*/

        $advancedPlan = Plan::create([
            'name' => 'Advanced',
            'description' => 'Advanced Information',
            'duration' => 30, // in days
            'price' => 100000, // in cents
            'currency' => 'usd',
        ]);

        $advancedPlanYearly = Plan::create([
            'name' => 'Advanced',
            'description' => 'Advanced Information',
            'duration' => 365, // in days
            'price' => 80000, // in cents
            'currency' => 'usd',
        ]);

        $customDesign = new Feature([
            'name' => 'custom design',
            'code' => 'custom_design',
            'description' => 'Custom Design',
            'type' => 'feature',
        ]);
        $customFeatures = new Feature([
            'name' => 'custom features',
            'code' => 'custom_features',
            'description' => 'Custom Features',
            'type' => 'feature',
        ]);
        $unlimitedRevision = new Feature([
            'name' => 'unlimited revision',
            'code' => 'unlimited_revision',
            'description' => 'Unlimited Revision',
            'type' => 'feature',
        ]);

        $advancedPlan->features()->saveMany([
            $customDesign,
            $customFeatures,
            $appSplashScreen,
            $appHomePage,
            $appProductPage,
            $appAddToCartPage,
            $appCheckoutPage,
            $appSignInPage,
            $appSignUpPage,
            $appFilterPage,
            $appSearchPage,
            $unlimitedRevision,
            $aiTitleNotification,
            $aiDescriptionNotification,
            $cartAbandonedNotification,
            $stockNotification,
            $appTerminationNotification,
            $recommendedProductNotification,
            $historyNotification,
            $instantNotifications,
            $scheduledNotifications,
            $notificationAnalytics,
            $cartAnalytics,
            $checkoutAnalytics,
            $searchProductAnalytics,
            $sellingProductAnalytics,
            $viewedProductAnalytics,
            $deepLinking,
            $facebookLiveSelling,
            $instagramLiveSelling,
            $youtubeLiveSelling,
            $ltrRtlSupport,
            $localization,
            $arFeature,
            $supportAppifyStore,
            $iOSAppPublication,
            $chatCustomerToAdmin,
            $chatAdminToAppify,
        ]);
        $advancedPlanYearly->features()->saveMany([
            $customDesign,
            $customFeatures,
            $appSplashScreen,
            $appHomePage,
            $appProductPage,
            $appAddToCartPage,
            $appCheckoutPage,
            $appSignInPage,
            $appSignUpPage,
            $appFilterPage,
            $appSearchPage,
            $unlimitedRevision,
            $aiTitleNotification,
            $aiDescriptionNotification,
            $cartAbandonedNotification,
            $stockNotification,
            $appTerminationNotification,
            $recommendedProductNotification,
            $historyNotification,
            $instantNotifications,
            $scheduledNotifications,
            $notificationAnalytics,
            $cartAnalytics,
            $checkoutAnalytics,
            $searchProductAnalytics,
            $sellingProductAnalytics,
            $viewedProductAnalytics,
            $deepLinking,
            $facebookLiveSelling,
            $instagramLiveSelling,
            $youtubeLiveSelling,
            $ltrRtlSupport,
            $localization,
            $arFeature,
            $supportAppifyStore,
            $iOSAppPublication,
            $chatCustomerToAdmin,
            $chatAdminToAppify,
        ]);
    }
}
