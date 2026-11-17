<?php

namespace Llaski\NovaScheduledJobs\Tests\Unit;

use Llaski\NovaScheduledJobs\Card;
use Llaski\NovaScheduledJobs\Columns;
use Llaski\NovaScheduledJobs\Tests\TestCase;
use Llaski\NovaScheduledJobs\Tool;
use PHPUnit\Framework\Attributes\Test;

class ConfigurableColumnsTest extends TestCase
{
    #[Test]
    public function tool_defaults_to_all_tool_columns(): void
    {
        $this->assertSame(Columns::toolDefaults(), (new Tool)->columns());
    }

    #[Test]
    public function card_defaults_to_card_columns(): void
    {
        $this->assertSame(Columns::cardDefaults(), (new Card)->columns());
    }

    #[Test]
    public function tool_only_filters_and_preserves_order(): void
    {
        $tool = (new Tool)->only(['dispatch', 'command', 'timezone', 'not-a-column']);

        $this->assertSame(['dispatch', 'command', 'timezone'], $tool->columns());
    }

    #[Test]
    public function tool_except_removes_columns_from_defaults(): void
    {
        $tool = (new Tool)->except(['timezone', 'runInBackground', 'evenInMaintenanceMode']);

        $this->assertSame([
            'command',
            'schedule',
            'expression',
            'nextDue',
            'withoutOverlapping',
            'onOneServer',
            'dispatch',
        ], $tool->columns());
    }

    #[Test]
    public function only_clears_except_and_vice_versa(): void
    {
        $tool = (new Tool)->except(['timezone'])->only(['command', 'dispatch']);

        $this->assertSame(['command', 'dispatch'], $tool->columns());

        $tool->except(['dispatch']);

        $this->assertSame([
            'command',
            'schedule',
            'expression',
            'timezone',
            'nextDue',
            'withoutOverlapping',
            'onOneServer',
            'runInBackground',
            'evenInMaintenanceMode',
        ], $tool->columns());
    }

    #[Test]
    public function card_only_ignores_tool_only_columns(): void
    {
        $card = (new Card)->only(['command', 'dispatch', 'nextDue']);

        $this->assertSame(['command', 'nextDue'], $card->columns());
    }

    #[Test]
    public function card_json_serialize_includes_resolved_columns(): void
    {
        $card = (new Card)->except(['expression']);

        $payload = $card->jsonSerialize();

        $this->assertSame(['command', 'nextDue'], $payload['columns']);
        $this->assertSame('NovaScheduledJobsCard', $payload['component']);
    }

    #[Test]
    public function config_only_applies_when_instance_not_set(): void
    {
        config([
            'nova-scheduled-jobs.tool.only' => ['command', 'schedule', 'dispatch'],
            'nova-scheduled-jobs.tool.except' => ['timezone'],
        ]);

        $this->assertSame(['command', 'schedule', 'dispatch'], (new Tool)->columns());
    }

    #[Test]
    public function config_except_applies_when_only_not_configured(): void
    {
        config([
            'nova-scheduled-jobs.card.except' => ['expression'],
        ]);

        $this->assertSame(['command', 'nextDue'], (new Card)->columns());
    }

    #[Test]
    public function instance_configuration_overrides_config_file(): void
    {
        config([
            'nova-scheduled-jobs.tool.only' => ['command', 'dispatch'],
        ]);

        $tool = (new Tool)->except(['timezone']);

        $this->assertSame([
            'command',
            'schedule',
            'expression',
            'nextDue',
            'withoutOverlapping',
            'onOneServer',
            'runInBackground',
            'evenInMaintenanceMode',
            'dispatch',
        ], $tool->columns());
    }

    #[Test]
    public function empty_config_lists_are_ignored(): void
    {
        config([
            'nova-scheduled-jobs.tool.only' => [],
            'nova-scheduled-jobs.tool.except' => [],
        ]);

        $this->assertSame(Columns::toolDefaults(), (new Tool)->columns());
    }
}
