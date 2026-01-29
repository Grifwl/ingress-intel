<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ZoneResource\Pages;
use App\Models\Zone;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ZoneResource extends Resource
{
    protected static ?string $model = Zone::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    
    protected static ?string $navigationLabel = 'Zones';
    
    protected static ?string $modelLabel = 'Zona';
    
    protected static ?string $pluralModelLabel = 'Zones';
    
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informació de la Zona')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nom de la Zona')
                            ->required()
                            ->maxLength(255),
                        
                        Forms\Components\Textarea::make('description')
                            ->label('Descripció')
                            ->rows(3),
                    ]),
                
                Forms\Components\Section::make('Ubicació')
                    ->schema([
                        Forms\Components\TextInput::make('city')
                            ->label('Ciutat')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('province')
                            ->label('Província')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('country')
                            ->label('País')
                            ->default('ES')
                            ->maxLength(2),
                    ])->columns(3),
                
                Forms\Components\Section::make('Coordenades GPS')
                    ->description('Coordenades del centre de la zona')
                    ->schema([
                        Forms\Components\TextInput::make('center_latitude')
                            ->label('Latitud')
                            ->numeric()
                            ->step(0.0000001)
                            ->placeholder('41.5767'),
                        
                        Forms\Components\TextInput::make('center_longitude')
                            ->label('Longitud')
                            ->numeric()
                            ->step(0.0000001)
                            ->placeholder('1.6174'),
                        
                        Forms\Components\Textarea::make('polygon_coordinates')
                            ->label('Polígon (JSON)')
                            ->helperText('Array de coordenades en format JSON per delimitar la zona')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])->columns(2),
                
                Forms\Components\Section::make('Agents en aquesta zona')
                    ->schema([
                        Forms\Components\Repeater::make('agents')
                            ->relationship('agents')
                            ->schema([
                                Forms\Components\Select::make('agent_id')
                                    ->label('Agent')
                                    ->relationship('agents', 'codename')
                                    ->searchable()
                                    ->required(),
                                
                                Forms\Components\Select::make('frequency')
                                    ->label('Freqüència')
                                    ->options([
                                        'daily' => 'Diari',
                                        'weekly' => 'Setmanal',
                                        'monthly' => 'Mensual',
                                        'occasionally' => 'Ocasional',
                                    ])
                                    ->default('occasionally'),
                                
                                Forms\Components\Textarea::make('notes')
                                    ->label('Notes')
                                    ->rows(2),
                            ])
                            ->columns(3)
                            ->defaultItems(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Zona')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('city')
                    ->label('Ciutat')
                    ->searchable()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('province')
                    ->label('Província')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\TextColumn::make('agents_count')
                    ->label('Agents')
                    ->counts('agents')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('center_latitude')
                    ->label('Latitud')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('center_longitude')
                    ->label('Longitud')
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creat')
                    ->dateTime('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('city')
                    ->label('Ciutat'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('name');
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
            'index' => Pages\ListZones::route('/'),
            'create' => Pages\CreateZone::route('/create'),
            'edit' => Pages\EditZone::route('/{record}/edit'),
        ];
    }
}
