<?php

namespace Tests\Feature\Web;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * Contract tests for the shared button component.
 */
final class ButtonComponentTest extends CIUnitTestCase
{
    public function testButtonClassUsesVariantAndAccessibleTouchSize(): void
    {
        $class = button_class('primary', 'md');

        $this->assertStringContainsString('bg-gold-gradient', $class);
        $this->assertStringContainsString('h-11', $class);
        $this->assertStringContainsString('focus-visible:ring-gold/30', $class);
        $this->assertStringContainsString('disabled:pointer-events-none', $class);

        $this->assertStringContainsString('h-10', button_class('outline', 'sm'));
        $this->assertStringContainsString('w-10', button_class('outline', 'icon-sm'));
    }

    public function testButtonRendersExactlyOneTypeAttribute(): void
    {
        $html = ui_button([
            'type' => 'submit',
            'content' => 'Simpan',
            'icon' => 'check',
        ]);

        $this->assertSame(1, substr_count($html, 'type="submit"'));
        $this->assertStringContainsString('<button', $html);
        $this->assertStringNotContainsString('<a ', $html);
    }

    public function testIconOnlyButtonHasAccessibleLabel(): void
    {
        $html = ui_button([
            'icon' => 'bell',
            'iconOnly' => true,
            'ariaLabel' => 'Notifikasi',
        ]);

        $this->assertStringContainsString('aria-label="Notifikasi"', $html);
        $this->assertStringContainsString('w-11', $html);
    }

    public function testDisabledLinkIsNotFocusable(): void
    {
        $html = ui_button([
            'href' => 'https://example.test',
            'content' => 'Tautan',
            'disabled' => true,
        ]);

        $this->assertStringContainsString('<a ', $html);
        $this->assertStringContainsString('aria-disabled="true"', $html);
        $this->assertStringContainsString('tabindex="-1"', $html);
    }

    public function testLoadingStateSetsAriaBusyAndSpinner(): void
    {
        $html = ui_button([
            'content' => 'Menyimpan',
            'loading' => true,
        ]);

        $this->assertStringContainsString('aria-busy="true"', $html);
        $this->assertStringContainsString('animate-spin', $html);
        $this->assertStringContainsString('disabled', $html);
    }
}
