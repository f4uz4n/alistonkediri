<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRatingToTestimonials extends Migration
{
    public function up()
    {
        $this->forge->addColumn('testimonials', [
            'rating' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'unsigned' => true,
                'default' => 5,
                'null' => true,
                'after' => 'testimonial',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('testimonials', 'rating');
    }
}
