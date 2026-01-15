<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('
            CREATE OR REPLACE FUNCTION notify_new_message()
            RETURNS TRIGGER AS $$
            BEGIN
                PERFORM pg_notify(\'new_message\', row_to_json(NEW)::text);
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::unprepared('
            CREATE TRIGGER new_message_trigger
            AFTER INSERT ON messages
            FOR EACH ROW
            EXECUTE FUNCTION notify_new_message();
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS new_message_trigger ON messages;');
        DB::unprepared('DROP FUNCTION IF EXISTS notify_new_message();');
    }
};