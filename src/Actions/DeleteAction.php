<?php

namespace SolutionForest\FilamentTree\Actions;

use Filament\Support\Actions\Concerns\CanCustomizeProcess;
use Illuminate\Database\Eloquent\Model;

class DeleteAction extends Action
{
    use CanCustomizeProcess;

    public static function getDefaultName(): ?string
    {
        return 'delete';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label(__('filament-support::actions/delete.single.label'));

        $this->modalHeading(fn (): string => __('filament-support::actions/delete.single.modal.heading', ['label' => $this->getRecordTitle()]));


        $this->successNotificationTitle(__('filament-support::actions/delete.single.messages.deleted'));

        $this->color('danger');

        $this->icon('heroicon-s-trash');
        
        $this->iconButton();

        $this->requiresConfirmation();

        $this->hidden(static function (Model $record): bool {
            if (! method_exists($record, 'trashed')) {
                return false;
            }

            return $record->trashed();
        });

        $this->action(function (): void {
            $result = $this->process(static fn (Model $record) => $record->delete());

            if (! $result) {
                $this->failure();

                return;
            }

            $this->success();
        });
    }
}
