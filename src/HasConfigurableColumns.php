<?php

namespace Llaski\NovaScheduledJobs;

trait HasConfigurableColumns
{
    /**
     * Explicit include list for columns.
     *
     * @var array<int, string>|null
     */
    protected ?array $onlyColumns = null;

    /**
     * Explicit exclude list for columns.
     *
     * @var array<int, string>|null
     */
    protected ?array $exceptColumns = null;

    /**
     * Show only the given columns (order preserved). Unknown keys are ignored.
     *
     * @param  array<int, string>  $columns
     * @return $this
     */
    public function only(array $columns): static
    {
        $this->onlyColumns = array_values($columns);
        $this->exceptColumns = null;

        return $this;
    }

    /**
     * Hide the given columns from the surface defaults.
     *
     * @param  array<int, string>  $columns
     * @return $this
     */
    public function except(array $columns): static
    {
        $this->exceptColumns = array_values($columns);
        $this->onlyColumns = null;

        return $this;
    }

    /**
     * Resolve the columns that should be shown for this surface.
     *
     * Precedence: instance only/except → config only/except → built-in defaults.
     *
     * @return array<int, string>
     */
    public function columns(): array
    {
        $defaults = $this->defaultColumns();
        $allowed = array_flip($defaults);

        if ($this->onlyColumns !== null) {
            return $this->filterAllowed($this->onlyColumns, $allowed);
        }

        if ($this->exceptColumns !== null) {
            return $this->applyExcept($defaults, $this->exceptColumns);
        }

        $configOnly = $this->configColumnList('only');

        if ($configOnly !== null) {
            return $this->filterAllowed($configOnly, $allowed);
        }

        $configExcept = $this->configColumnList('except');

        if ($configExcept !== null) {
            return $this->applyExcept($defaults, $configExcept);
        }

        return $defaults;
    }

    /**
     * Default columns for this surface.
     *
     * @return array<int, string>
     */
    abstract protected function defaultColumns(): array;

    /**
     * Config key under nova-scheduled-jobs for this surface (tool|card).
     */
    abstract protected function columnsConfigKey(): string;

    /**
     * @return array<int, string>|null
     */
    protected function configColumnList(string $key): ?array
    {
        $value = config('nova-scheduled-jobs.'.$this->columnsConfigKey().'.'.$key);

        if (! is_array($value) || $value === []) {
            return null;
        }

        return array_values($value);
    }

    /**
     * @param  array<int, string>  $columns
     * @param  array<string, int>  $allowed
     * @return array<int, string>
     */
    protected function filterAllowed(array $columns, array $allowed): array
    {
        return array_values(array_filter(
            $columns,
            fn (string $column) => isset($allowed[$column])
        ));
    }

    /**
     * @param  array<int, string>  $defaults
     * @param  array<int, string>  $except
     * @return array<int, string>
     */
    protected function applyExcept(array $defaults, array $except): array
    {
        $excluded = array_flip($except);

        return array_values(array_filter(
            $defaults,
            fn (string $column) => ! isset($excluded[$column])
        ));
    }
}
