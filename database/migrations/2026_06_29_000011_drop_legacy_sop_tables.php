<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('sop_box_user');
        Schema::dropIfExists('sop_document_user');
        Schema::dropIfExists('sop_box_items');
        Schema::dropIfExists('sop_boxes');
        Schema::dropIfExists('sop_documents');
    }

    public function down(): void
    {
        // Legacy SOP tables removed intentionally — not recreated.
    }
};
