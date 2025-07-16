<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\EmployeeResource\Pages;
use App\Filament\Admin\Resources\EmployeeResource\RelationManagers;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Position;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Company Management';
    protected static ?int $navigationSort = 0;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
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
                Forms\Components\TextInput::make('employee_code')
                    ->label('Emp_Code')
                    ->disabled()
                    ->dehydrated(true)
                    ->required()
                    ->default(fn() => 'EMP-' . str_pad(\App\Models\Employee::query()->max('id') + 1, 5, '0', STR_PAD_LEFT))
                    ->unique(ignoreRecord: true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('department.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('position.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('employee_code')
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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}
