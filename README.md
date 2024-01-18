## Daily Do Task Manager Plugin for Your Website

This plugin was developed to address the need for a streamlined daily task management solution for businesses, specifically designed to integrate seamlessly into your website. Say goodbye to the hassle of external task management systems and embrace this simple yet powerful daily task checklist.

## Key Features

- **Effortless Task Management:** With this plugin, you can effortlessly manage your daily tasks directly from your website.
- **Unlimited To-Do Items:** Add as many to-do items as you need without any limitations.
- **User-Friendly Dashboard Widget:** Log in every day to your website's dashboard and easily access your daily tasks at a glance.
- **Seamless Task Completion:** Mark tasks as complete with ease, whether it's directly from the dashboard widget, the widget modal, or the dedicated to-do edit page.
- **Developer Friendly:** Integrate and extend to fit your business.


## Requirements

- Botble core 7.1.0 or higher.
- Skillcraft Core Plugin - v2.0.0

## Installation

**Install via Admin Panel**

- Important:
  "You need to disable the "Daily Do" plugin if previously installed. This re-release of the plugin has renmaed the folder to "sc-daily-do". 
  
  ##Do not use## the plugins remove button fromt he admin area, manually remove the folder and contents directly from the server. If you do not have any data, you can use the remove button.

Go to the **Admin Panel** and click on the **Plugins** tab. Click on the "Add new" button, find the **Daily Dos** plugin and click on the "Install" button.


**Install manually**

1. Download the plugin
2. Extract the downloaded file and upload the extracted folder to the `platform/plugins` directory.
3. Go to **Admin** > **Plugins** and click on the **Activate** button.


## Extending

Easily register any model with Daily Dos, and instantly add task management to your model.

To register Your Model, in your service providers boot method

```php
if (defined('DAILY_DO_MODULE_SCREEN_NAME')) {
    \Skillcraft\DailyDo\Supports\DailyDoManager::registerHooks(YourModel::class, 'Model Name');
}
```

Your model only needs to implement 1 Method, This method returns the list of to-dos for the current day when the cron runs daily.

In your model
```php
public function getDailyDoTasks():array
    {
        $daily_tasks = [];

        /**
         * Add Your Code Here.
         * 
         * The end array should be as follows.
         * 
         * $daily_tasks[] = [
            'module' => {Model Here},
            'title' => {Task Title},
            'description' => {Task Information},
          ];
         */
       
        return $daily_tasks;
    }
```


## Optional But Recommended

If your source model is updated, and the task for it would change, its important to change the Models extended class as follow.
This automates and syncs tasks to any new information when your source model is updated.

```php
use Skillcraft\Core\Models\CoreModel;

class YourModel extends CoreModel ...
```

# Manually Syncing

If prefer to manually sync your models daily dos, then simply call this class and pass your model.

```php
(new \Skillcraft\DailyDo\Actions\SyncDailyDoAction)->handle($yourModel);
```

Hope you enjoy my plugin, have suggestions, feedback or contributions, feel free to share.


## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.