<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SalesResource\Pages;
use App\Filament\Admin\Resources\SalesResource\RelationManagers;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Sales;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalesResource extends Resource
{
    protected static ?string $model = Sales::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Forms\Components\Select::make('user_id')
                //     ->relationship('user', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->required(),
                // Forms\Components\Select::make('employee_id')
                //     ->relationship('employee', 'id')
                //     ->getOptionLabelFromRecordUsing(fn($record) => $record->user?->name)
                //     ->searchable()
                //     ->preload()
                //     ->required(),
                // Forms\Components\Select::make('department_id')
                //     ->relationship('department', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->required(),
                // Forms\Components\Select::make('position_id')
                //     ->relationship('position', 'name')
                //     ->searchable()
                //     ->preload()
                //     ->required(),
                // Forms\Components\TextInput::make('phone')
                //     ->tel()
                //     ->required()
                //     ->maxLength(255),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),

                Forms\Components\Select::make('employee_id')
                    ->label('Sales')
                    ->options(function () {
                        return Employee::whereHas('department', function ($query) {
                                $query->where('name', 'Sales');
                            })
                            ->with('user') 
                            ->get()
                            ->mapWithKeys(function ($employee) {
                                return [$employee->id => $employee->user->name ?? 'Tanpa Nama'];
                            });
                    })
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $employee = Employee::find($state);
                        $set('user_id', $employee?->user_id); 
                }),
            
                Forms\Components\Select::make('branch_id')
                    ->label('Cabang')
                    ->relationship('branch', 'name')
                    ->preload()
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $set) {
                        // Reset department_id ketika branch berubah
                        $set('department_id', null);
                    }),
                    
                Forms\Components\Select::make('department_id')
                    ->label('Department')
                    ->required()
                    ->options(function (callable $get) {
                        $branchId = $get('branch_id');
                
                        if (!$branchId) {
                            return Department::pluck('name', 'id'); 
                        }
                
                        return Department::where('branch_id', $branchId)
                            ->pluck('name', 'id');
                    })
                    ->reactive() 
                    ->disabled(fn (callable $get) => !$get('branch_id'))
                    ->dehydrated(),
                Forms\Components\Select::make('position_id')
                    ->label('Position')
                    ->required()
                    ->options(function (callable $get) {
                        $departmentId = $get('department_id');
                
                        if (!$departmentId) {
                            return Position::pluck('name', 'id'); 
                        }
                
                        return Position::where('department_id', $departmentId)
                            ->pluck('name', 'id');
                    })
                    ->reactive() 
                    ->disabled(fn (callable $get) => !$get('department_id'))
                    ->dehydrated(),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->required()
                    ->prefix('62+')
                    ->maxLength(15)
                    ->placeholder('81234567890')
                    ->helperText('Masukkan nomor tanpa angka 0 di depan. Contoh: 81234567890')
                    ->rule('regex:/^[0-9]{9,13}$/')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('user.name')
                //     ->label('User')
                //     ->searchable()
                //     ->sortable(),

                Tables\Columns\TextColumn::make('employee.user.name')
                    ->label('Employee')
                    ->searchable(),

                Tables\Columns\TextColumn::make('employee.branch.name')
                    ->label('Cabang')
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('department.name')
                    ->label('Department')
                    ->sortable(),

                Tables\Columns\TextColumn::make('position.name')
                    ->label('Position')
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSales::route('/'),
            'create' => Pages\CreateSales::route('/create'),
            'edit' => Pages\EditSales::route('/{record}/edit'),
        ];
    }
}
