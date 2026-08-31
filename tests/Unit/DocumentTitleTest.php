<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class DocumentTitleTest extends TestCase
{
    public function test_home_and_empty_titles_are_the_site_name(): void
    {
        $this->assertSame('BNS School', bns_document_title(null));
        $this->assertSame('BNS School', bns_document_title(''));
        $this->assertSame('BNS School', bns_document_title('Home'));
        $this->assertSame('BNS School', bns_document_title('BNS School'));
    }

    public function test_inner_pages_append_the_site_name(): void
    {
        $this->assertSame('Contact Us — BNS School', bns_document_title('Contact Us'));
        $this->assertSame('Registration — BNS School', bns_document_title('Registration'));
    }

    public function test_titles_that_already_include_the_brand_are_left_alone(): void
    {
        $this->assertSame(
            'Our Values – Business Navachar School',
            bns_document_title('Our Values – Business Navachar School')
        );
        $this->assertSame(
            'Pay Now — BNS School',
            bns_document_title('Pay Now — BNS School')
        );
    }
}
