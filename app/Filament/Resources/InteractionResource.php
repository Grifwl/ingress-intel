<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InteractionResource\Pages;
use App\Models\Interaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class InteractionResource extends Resource
{
    protected static ?string $model = Interaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';
    
    protected static ?string $navigationLabel = 'Interaccions';
    
    protected static ?string $modelLabel = 'Interacció';
    
    protected static ?string $pluralModelLabel = 'Interaccions';
    
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informació Bàsica')
                    ->schema([
                        Forms\Components\Select::make('agent_id')
                            ->label('Agent')
                            ->relationship('agent', 'codename')
                            ->searchable()
                            ->required()
                            ->preload(),
                        
                        Forms\Components\Select::make('interaction_type_id')
                            ->label('Tipus d\'Interacció')
                            ->relationship('type', 'name')
                            ->required()
                            ->preload(),
                        
                        Forms\Components\TextInput::make('title')
                            ->label('Títol')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Quan i On')
                    ->schema([
                        Forms\Components\DatePicker::make('date')
                            ->label('Data')
                            ->required()
                            ->native(false)
                            ->default(now()),
                        
                        Forms\Components\TimePicker::make('time')
                            ->label('Hora')
                            ->seconds(false),
                        
                        Forms\Components\TextInput::make('location')
                            ->label('Ubicació')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('latitude')
                            ->label('Latitud')
                            ->numeric()
                            ->step(0.0000001),
                        
                        Forms\Components\TextInput::make('longitude')
                            ->label('Longitud')
                            ->numeric()
                            ->step(0.0000001),
                        
                        Forms\Components\Select::make('impact_level')
                            ->label('Nivell d\'Impacte')
                            ->options([
                                1 => '1 - Mínim',
                                2 => '2 - Baix',
                                3 => '3 - Mitjà',
                                4 => '4 - Alt',
                                5 => '5 - Crític',
                            ])
                            ->default(3),
                    ])->columns(3),
                
                Forms\Components\Section::make('Detalls')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->label('Descripció')
                            ->rows(4)
                            ->columnSpanFull(),
                        
                        Forms\Components\Textarea::make('outcome')
                            ->label('Resultat')
                            ->rows(3)
                            ->columnSpanFull(),
                        
                        Forms\Components\TagsInput::make('other_agents')
                            ->label('Altres Agents Involucrats')
                            ->placeholder('Nom d\'agent i prem Enter')
                            ->helperText('Afegeix els noms d\'altres agents que van participar')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable()
                    ->searchable(),
                
                Tables\Columns\TextColumn::make('agent.codename')
                    ->label('Agent')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\BadgeColumn::make('type.name')
                    ->label('Tipus')
                    ->colors([
                        'success' => fn ($state, $record) => $record->type->is_positive ?? true,
                        'danger' => fn ($state, $record) => !($record->type->is_positive ?? true),
                    ]),
                
                Tables\Columns\TextColumn::make('title')
                    ->label('Títol')
                    ->searchable()
                    ->limit(40),
                
                Tables\Columns\TextColumn::make('location')
                    ->label('Ubicació')
                    ->searchable()
                    ->toggleable()
                    ->limit(30),
                
                Tables\Columns\TextColumn::make('impact_level')
                    ->label('Impacte')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        1 => 'Mínim',
                        2 => 'Baix',
                        3 => 'Mitjà',
                        4 => 'Alt',
                        5 => 'Crític',
                        default => '-'
                    })
                    ->colors([
                        'secondary' => 1,
                        'info' => 2,
                        'warning' => 3,
                        'danger' => 4,
                        'danger' => 5,
                    ]),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('interaction_type_id')
                    ->label('Tipus')
                    ->relationship('type', 'name'),
                
                Tables\Filters\SelectFilter::make('agent_id')
                    ->label('Agent')
                    ->relationship('agent', 'codename')
                    ->searchable(),
                
                Tables\Filters\SelectFilter::make('impact_level')
                    ->label('Nivell d\'Impacte')
                    ->options([
                        1 => '1 - Mínim',
                        2 => '2 - Baix',
                        3 => '3 - Mitjà',
                        4 => '4 - Alt',
                        5 => '5 - Crític',
                    ]),
                
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('Des de'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Fins a'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['created_from'], fn ($q) => $q->whereDate('date', '>=', $data['created_from']))
                            ->when($data['created_until'], fn ($q) => $q->whereDate('date', '<=', $data['created_until']));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('date', 'desc');
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
            'index' => Pages\ListInteractions::route('/'),
            'create' => Pages\CreateInteraction::route('/create'),
            'edit' => Pages\EditInteraction::route('/{record}/edit'),
        ];
    }
}
