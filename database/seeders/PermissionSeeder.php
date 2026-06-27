<?php

namespace Database\Seeders;

use App\Models\User\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Permissions with localized display names (clear & consistent).
     * key: value used in code/API & DB column; name_ar, name_en: display names.
     */
    protected array $permissions = [
        // ——— Administrators ———
        ['key' => 'view_admins', 'name_ar' => 'عرض قائمة المسؤولين', 'name_en' => 'View administrators list'],
        ['key' => 'create_admins', 'name_ar' => 'إضافة مسؤول جديد', 'name_en' => 'Add new administrator'],
        ['key' => 'edit_admins', 'name_ar' => 'تعديل بيانات مسؤول', 'name_en' => 'Edit administrator'],
        ['key' => 'delete_admins', 'name_ar' => 'حذف مسؤول', 'name_en' => 'Delete administrator'],
        ['key' => 'restore_admins', 'name_ar' => 'استعادة مسؤول محذوف', 'name_en' => 'Restore deleted administrator'],
        ['key' => 'force_delete_admins', 'name_ar' => 'حذف مسؤول نهائياً', 'name_en' => 'Permanently delete administrator'],

        // ——— Roles ———
        ['key' => 'view_roles', 'name_ar' => 'عرض قائمة الأدوار', 'name_en' => 'View roles list'],
        ['key' => 'create_roles', 'name_ar' => 'إضافة دور جديد', 'name_en' => 'Add new role'],
        ['key' => 'edit_roles', 'name_ar' => 'تعديل دور', 'name_en' => 'Edit role'],
        ['key' => 'delete_roles', 'name_ar' => 'حذف دور', 'name_en' => 'Delete role'],

        // ——— Permissions ———
        ['key' => 'view_permissions', 'name_ar' => 'عرض قائمة الصلاحيات', 'name_en' => 'View permissions list'],

        // ——— Dashboard ———
        ['key' => 'view_dashboard', 'name_ar' => 'عرض لوحة التحكم', 'name_en' => 'View dashboard'],

        // ——— Content Pages ———
        ['key' => 'view_content_pages', 'name_ar' => 'عرض صفحات المحتوى', 'name_en' => 'View content pages'],
        ['key' => 'create_content_pages', 'name_ar' => 'إضافة صفحة محتوى جديدة', 'name_en' => 'Add new content page'],
        ['key' => 'edit_content_pages', 'name_ar' => 'تعديل صفحة المحتوى', 'name_en' => 'Edit content page'],
        ['key' => 'delete_content_pages', 'name_ar' => 'حذف صفحة المحتوى', 'name_en' => 'Delete content page'],

        // ——— Users ———
        ['key' => 'view_users', 'name_ar' => 'عرض قائمة المستخدمين', 'name_en' => 'View users list'],
        ['key' => 'create_users', 'name_ar' => 'إضافة مستخدم جديد', 'name_en' => 'Add new user'],
        ['key' => 'edit_users', 'name_ar' => 'تعديل بيانات مستخدم', 'name_en' => 'Edit user'],
        ['key' => 'delete_users', 'name_ar' => 'حذف مستخدم', 'name_en' => 'Delete user'],
        ['key' => 'restore_users', 'name_ar' => 'استعادة مستخدم محذوف', 'name_en' => 'Restore deleted user'],
        ['key' => 'force_delete_users', 'name_ar' => 'حذف مستخدم نهائياً', 'name_en' => 'Permanently delete user'],

        // ——— Posts ———
        ['key' => 'view_posts', 'name_ar' => 'عرض المنشورات', 'name_en' => 'View posts'],
        ['key' => 'create_posts', 'name_ar' => 'إضافة منشور', 'name_en' => 'Create post'],
        ['key' => 'edit_posts', 'name_ar' => 'تعديل المنشورات', 'name_en' => 'Edit posts'],
        ['key' => 'delete_posts', 'name_ar' => 'حذف المنشورات', 'name_en' => 'Delete posts'],
        ['key' => 'restore_posts', 'name_ar' => 'استعادة المنشورات', 'name_en' => 'Restore posts'],
        ['key' => 'force_delete_posts', 'name_ar' => 'حذف منشور نهائياً', 'name_en' => 'Permanently delete posts'],

        // ——— Post comment presets ———
        ['key' => 'view_post_comment_presets', 'name_ar' => 'عرض التعليقات الجاهزة', 'name_en' => 'View comment presets'],
        ['key' => 'create_post_comment_presets', 'name_ar' => 'إضافة تعليق جاهز', 'name_en' => 'Create comment preset'],
        ['key' => 'edit_post_comment_presets', 'name_ar' => 'تعديل تعليق جاهز', 'name_en' => 'Edit comment preset'],
        ['key' => 'delete_post_comment_presets', 'name_ar' => 'حذف تعليق جاهز', 'name_en' => 'Delete comment preset'],
        ['key' => 'restore_post_comment_presets', 'name_ar' => 'استعادة تعليق جاهز', 'name_en' => 'Restore comment preset'],
        ['key' => 'force_delete_post_comment_presets', 'name_ar' => 'حذف تعليق جاهز نهائياً', 'name_en' => 'Permanently delete comment preset'],

        // ——— Circles ———
        ['key' => 'view_circles', 'name_ar' => 'عرض الدوائر', 'name_en' => 'View circles'],
        ['key' => 'create_circles', 'name_ar' => 'إضافة دائرة', 'name_en' => 'Create circle'],
        ['key' => 'edit_circles', 'name_ar' => 'تعديل الدوائر', 'name_en' => 'Edit circles'],
        ['key' => 'delete_circles', 'name_ar' => 'حذف الدوائر', 'name_en' => 'Delete circles'],
        ['key' => 'restore_circles', 'name_ar' => 'استعادة دائرة محذوفة', 'name_en' => 'Restore circles'],
        ['key' => 'force_delete_circles', 'name_ar' => 'حذف دائرة نهائياً', 'name_en' => 'Permanently delete circles'],

        // ——— Circle challenges ———
        ['key' => 'view_circle_challenges', 'name_ar' => 'عرض تحديات الدوائر', 'name_en' => 'View circle challenges'],
        ['key' => 'create_circle_challenges', 'name_ar' => 'إضافة تحدي', 'name_en' => 'Create circle challenge'],
        ['key' => 'edit_circle_challenges', 'name_ar' => 'تعديل تحديات الدوائر', 'name_en' => 'Edit circle challenges'],
        ['key' => 'delete_circle_challenges', 'name_ar' => 'حذف تحديات الدوائر', 'name_en' => 'Delete circle challenges'],
        ['key' => 'restore_circle_challenges', 'name_ar' => 'استعادة تحدي محذوف', 'name_en' => 'Restore circle challenges'],
        ['key' => 'force_delete_circle_challenges', 'name_ar' => 'حذف تحدي نهائياً', 'name_en' => 'Permanently delete circle challenges'],

        // ——— Help asks (محتاج رأيكم) ———
        ['key' => 'view_help_asks', 'name_ar' => 'عرض أسئلة محتاج رأيكم', 'name_en' => 'View help asks'],
        ['key' => 'create_help_asks', 'name_ar' => 'إضافة سؤال', 'name_en' => 'Create help ask'],
        ['key' => 'edit_help_asks', 'name_ar' => 'تعديل أسئلة محتاج رأيكم', 'name_en' => 'Edit help asks'],
        ['key' => 'delete_help_asks', 'name_ar' => 'حذف أسئلة محتاج رأيكم', 'name_en' => 'Delete help asks'],
        ['key' => 'restore_help_asks', 'name_ar' => 'استعادة سؤال محذوف', 'name_en' => 'Restore help asks'],
        ['key' => 'force_delete_help_asks', 'name_ar' => 'حذف سؤال نهائياً', 'name_en' => 'Permanently delete help asks'],

        // ——— Stories ———
        ['key' => 'view_stories', 'name_ar' => 'عرض الحكايات', 'name_en' => 'View stories'],
        ['key' => 'create_stories', 'name_ar' => 'إضافة تصنيف حكايات', 'name_en' => 'Create stories'],
        ['key' => 'edit_stories', 'name_ar' => 'تعديل الحكايات', 'name_en' => 'Edit stories'],
        ['key' => 'delete_stories', 'name_ar' => 'حذف الحكايات', 'name_en' => 'Delete stories'],
        ['key' => 'restore_stories', 'name_ar' => 'استعادة حكاية محذوفة', 'name_en' => 'Restore stories'],
        ['key' => 'force_delete_stories', 'name_ar' => 'حذف حكاية نهائياً', 'name_en' => 'Permanently delete stories'],

        // ——— Wheel challenges (عجلة معلش) ———
        ['key' => 'view_wheel_challenges', 'name_ar' => 'عرض تحديات العجلة', 'name_en' => 'View wheel challenges'],
        ['key' => 'create_wheel_challenges', 'name_ar' => 'إضافة تحدي عجلة', 'name_en' => 'Create wheel challenge'],
        ['key' => 'edit_wheel_challenges', 'name_ar' => 'تعديل تحديات العجلة', 'name_en' => 'Edit wheel challenges'],
        ['key' => 'delete_wheel_challenges', 'name_ar' => 'حذف تحديات العجلة', 'name_en' => 'Delete wheel challenges'],
        ['key' => 'restore_wheel_challenges', 'name_ar' => 'استعادة تحدي عجلة محذوف', 'name_en' => 'Restore wheel challenges'],
        ['key' => 'force_delete_wheel_challenges', 'name_ar' => 'حذف تحدي عجلة نهائياً', 'name_en' => 'Permanently delete wheel challenges'],

        // ——— Moods ———
        ['key' => 'view_moods', 'name_ar' => 'عرض المزاجات', 'name_en' => 'View moods'],
        ['key' => 'create_moods', 'name_ar' => 'إضافة مزاج', 'name_en' => 'Create mood'],
        ['key' => 'edit_moods', 'name_ar' => 'تعديل المزاجات', 'name_en' => 'Edit moods'],
        ['key' => 'delete_moods', 'name_ar' => 'حذف المزاجات', 'name_en' => 'Delete moods'],
        ['key' => 'restore_moods', 'name_ar' => 'استعادة مزاج محذوف', 'name_en' => 'Restore moods'],
        ['key' => 'force_delete_moods', 'name_ar' => 'حذف مزاج نهائياً', 'name_en' => 'Permanently delete moods'],

        // ——— Daily card tips (سحبة النهاردة) ———
        ['key' => 'view_daily_card_tips', 'name_ar' => 'عرض كروت سحبة النهاردة', 'name_en' => 'View daily card tips'],
        ['key' => 'create_daily_card_tips', 'name_ar' => 'إضافة كارت سحبة', 'name_en' => 'Create daily card tip'],
        ['key' => 'edit_daily_card_tips', 'name_ar' => 'تعديل كروت سحبة النهاردة', 'name_en' => 'Edit daily card tips'],
        ['key' => 'delete_daily_card_tips', 'name_ar' => 'حذف كروت سحبة النهاردة', 'name_en' => 'Delete daily card tips'],
        ['key' => 'restore_daily_card_tips', 'name_ar' => 'استعادة كارت محذوف', 'name_en' => 'Restore daily card tips'],
        ['key' => 'force_delete_daily_card_tips', 'name_ar' => 'حذف كارت نهائياً', 'name_en' => 'Permanently delete daily card tips'],

        // ——— Achievements (الإنجازات) ———
        ['key' => 'view_achievements', 'name_ar' => 'عرض الإنجازات', 'name_en' => 'View achievements'],
        ['key' => 'create_achievements', 'name_ar' => 'إضافة إنجاز', 'name_en' => 'Create achievement'],
        ['key' => 'edit_achievements', 'name_ar' => 'تعديل الإنجازات', 'name_en' => 'Edit achievements'],
        ['key' => 'delete_achievements', 'name_ar' => 'حذف الإنجازات', 'name_en' => 'Delete achievements'],
        ['key' => 'restore_achievements', 'name_ar' => 'استعادة إنجاز محذوف', 'name_en' => 'Restore achievements'],
        ['key' => 'force_delete_achievements', 'name_ar' => 'حذف إنجاز نهائياً', 'name_en' => 'Permanently delete achievements'],

        // ——— Room decorations (تزيينات البيت) ———
        ['key' => 'view_room_decorations', 'name_ar' => 'عرض تزيينات البيت', 'name_en' => 'View room decorations'],
        ['key' => 'create_room_decorations', 'name_ar' => 'إضافة تزيين', 'name_en' => 'Create room decoration'],
        ['key' => 'edit_room_decorations', 'name_ar' => 'تعديل تزيينات البيت', 'name_en' => 'Edit room decorations'],
        ['key' => 'delete_room_decorations', 'name_ar' => 'حذف تزيينات البيت', 'name_en' => 'Delete room decorations'],
        ['key' => 'restore_room_decorations', 'name_ar' => 'استعادة تزيين محذوف', 'name_en' => 'Restore room decorations'],
        ['key' => 'force_delete_room_decorations', 'name_ar' => 'حذف تزيين نهائياً', 'name_en' => 'Permanently delete room decorations'],

        // ——— Companion suggestions (رفيق معلش) ———
        ['key' => 'view_companion_suggestions', 'name_ar' => 'عرض اقتراحات الرفيق', 'name_en' => 'View companion suggestions'],
        ['key' => 'create_companion_suggestions', 'name_ar' => 'إضافة اقتراح رفيق', 'name_en' => 'Create companion suggestion'],
        ['key' => 'edit_companion_suggestions', 'name_ar' => 'تعديل اقتراحات الرفيق', 'name_en' => 'Edit companion suggestions'],
        ['key' => 'delete_companion_suggestions', 'name_ar' => 'حذف اقتراحات الرفيق', 'name_en' => 'Delete companion suggestions'],
        ['key' => 'restore_companion_suggestions', 'name_ar' => 'استعادة اقتراح محذوف', 'name_en' => 'Restore companion suggestions'],
        ['key' => 'force_delete_companion_suggestions', 'name_ar' => 'حذف اقتراح نهائياً', 'name_en' => 'Permanently delete companion suggestions'],

        // ——— Companion replies (رفيق معلش) ———
        ['key' => 'view_companion_replies', 'name_ar' => 'عرض ردود الرفيق', 'name_en' => 'View companion replies'],
        ['key' => 'create_companion_replies', 'name_ar' => 'إضافة رد رفيق', 'name_en' => 'Create companion reply'],
        ['key' => 'edit_companion_replies', 'name_ar' => 'تعديل ردود الرفيق', 'name_en' => 'Edit companion replies'],
        ['key' => 'delete_companion_replies', 'name_ar' => 'حذف ردود الرفيق', 'name_en' => 'Delete companion replies'],
        ['key' => 'restore_companion_replies', 'name_ar' => 'استعادة رد محذوف', 'name_en' => 'Restore companion replies'],
        ['key' => 'force_delete_companion_replies', 'name_ar' => 'حذف رد نهائياً', 'name_en' => 'Permanently delete companion replies'],

        // ——— Companion settings (رفيق معلش) ———
        ['key' => 'manage_companion_settings', 'name_ar' => 'إدارة إعدادات الرفيق', 'name_en' => 'Manage companion settings'],

        // ——— Community seasons (شجرة الدعم) ———
        ['key' => 'view_community_seasons', 'name_ar' => 'عرض مواسم المجتمع', 'name_en' => 'View community seasons'],
        ['key' => 'create_community_seasons', 'name_ar' => 'إضافة موسم مجتمع', 'name_en' => 'Create community season'],
        ['key' => 'edit_community_seasons', 'name_ar' => 'تعديل مواسم المجتمع', 'name_en' => 'Edit community seasons'],
        ['key' => 'delete_community_seasons', 'name_ar' => 'حذف مواسم المجتمع', 'name_en' => 'Delete community seasons'],
        ['key' => 'restore_community_seasons', 'name_ar' => 'استعادة موسم محذوف', 'name_en' => 'Restore community seasons'],
        ['key' => 'force_delete_community_seasons', 'name_ar' => 'حذف موسم نهائياً', 'name_en' => 'Permanently delete community seasons'],

        // ——— Profile avatars ———
        ['key' => 'view_avatars', 'name_ar' => 'عرض أفاتارات الملف الشخصي', 'name_en' => 'View profile avatars'],
        ['key' => 'create_avatars', 'name_ar' => 'إضافة أفاتار', 'name_en' => 'Add profile avatar'],
        ['key' => 'delete_avatars', 'name_ar' => 'حذف أفاتار', 'name_en' => 'Delete profile avatar'],

        // ——— Support Tickets ———
        ['key' => 'view_support_tickets', 'name_ar' => 'عرض تذاكر الدعم', 'name_en' => 'View support tickets'],
        ['key' => 'create_support_tickets', 'name_ar' => 'إنشاء تذكرة دعم', 'name_en' => 'Create support ticket'],
        ['key' => 'edit_support_tickets', 'name_ar' => 'تعديل تذكرة الدعم', 'name_en' => 'Edit support ticket'],
        ['key' => 'delete_support_tickets', 'name_ar' => 'حذف تذكرة الدعم', 'name_en' => 'Delete support ticket'],
        ['key' => 'restore_support_tickets', 'name_ar' => 'استعادة تذكرة الدعم', 'name_en' => 'Restore support ticket'],
        ['key' => 'force_delete_support_tickets', 'name_ar' => 'حذف نهائي لتذكرة الدعم', 'name_en' => 'Force delete support ticket'],
        ['key' => 'manage_support_ticket_status', 'name_ar' => 'إدارة حالة تذكرة الدعم', 'name_en' => 'Manage support ticket status'],
        ['key' => 'manage_support_ticket_priority', 'name_ar' => 'إدارة أولوية تذكرة الدعم', 'name_en' => 'Manage support ticket priority'],
        ['key' => 'create_support_ticket_logs', 'name_ar' => 'إضافة سجل تذكرة الدعم', 'name_en' => 'Add support ticket log'],
        ['key' => 'delete_support_ticket_logs', 'name_ar' => 'حذف سجل تذكرة الدعم', 'name_en' => 'Delete support ticket log'],
        ['key' => 'restore_support_ticket_logs', 'name_ar' => 'استعادة سجل تذكرة الدعم', 'name_en' => 'Restore support ticket log'],
        ['key' => 'force_delete_support_ticket_logs', 'name_ar' => 'حذف نهائي لسجل تذكرة الدعم', 'name_en' => 'Force delete support ticket log'],

        // ——— Notifications (in-app + push audit) ———
        ['key' => 'view_notifications', 'name_ar' => 'عرض الإشعارات والوارد', 'name_en' => 'View notifications (inbox & log)'],
        ['key' => 'send_notifications', 'name_ar' => 'إرسال إشعارات لمستخدمين أو مسؤولين', 'name_en' => 'Send targeted notifications'],
        ['key' => 'view_notification_broadcasts', 'name_ar' => 'عرض سجل البث الموضوعي', 'name_en' => 'View notification broadcast log'],
        ['key' => 'send_notification_broadcasts', 'name_ar' => 'إرسال بث إشعارات (موضوع/FCM)', 'name_en' => 'Send topic / broadcast notifications'],
    ];

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $activeKeys = array_column($this->permissions, 'key');

        Permission::query()
            ->where('guard_name', 'admin')
            ->whereNotIn('key', $activeKeys)
            ->delete();

        foreach ($this->permissions as $item) {
            Permission::query()->updateOrCreate(
                ['key' => $item['key'], 'guard_name' => 'admin'],
                ['name' => $item['key'], 'name_ar' => $item['name_ar'], 'name_en' => $item['name_en']],
            );
        }
    }
}
