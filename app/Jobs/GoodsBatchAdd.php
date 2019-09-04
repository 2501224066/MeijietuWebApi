<?php


namespace App\Jobs;

use App\Models\Attr\Modular;
use App\Models\Attr\Theme;
use App\Server\SelfMediaImport;
use App\Server\SoftArticleImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class  GoodsBatchAdd implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $uid;

    protected $excel_path;

    protected $modular_id;

    protected $theme_id;

    public function __construct($uid, $excel_path, $modular_id, $theme_id)
    {
        $this->uid        = $uid;
        $this->excel_path = $excel_path;
        $this->modular_id = $modular_id;
        $this->theme_id   = $theme_id;
    }

    public function handle()
    {
        $uid        = $this->uid;
        $excel_path = $this->excel_path;
        $modular_id = $this->modular_id;
        $theme_id   = $this->theme_id;

        // 软文批量入驻
        if ((Modular::whereModularId($modular_id)->value('modular_name') == '软文营销')
            && (Theme::whereThemeId($theme_id)->value('theme_name') == '软文')) {
            SoftArticleImport::OP($uid, $excel_path, $modular_id, $theme_id);
        }

        // 自媒体入驻
        if (Modular::whereModularId($modular_id)->value('modular_name') == '自媒体营销'){
            SelfMediaImport::OP($uid, $excel_path, $modular_id, $theme_id);
        }
    }
}