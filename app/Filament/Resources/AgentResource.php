<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AgentResource\Pages;
use App\Models\Agent;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class AgentResource extends Resource
{
    protected static ?string $model = Agent::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    
    protected static ?string $navigationLabel = 'Agents';
    
    protected static ?string $modelLabel = 'Agent';
    
    protected static ?string $pluralModelLabel = 'Agents';
    
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informació Bàsica')
                    ->schema([
                        Forms\Components\TextInput::make('codename')
                            ->label('Nom d\'Agent')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('real_name')
                            ->label('Nom Real')
                            ->maxLength(255),
                        
                        Forms\Components\Select::make('current_faction')
                            ->label('Facció Actual')
                            ->options([
                                'resistance' => 'Resistència',
                                'enlightened' => 'Il·luminats',
                            ])
                            ->required()
                            ->default('resistance'),
                        
                        Forms\Components\TextInput::make('level')
                            ->label('Nivell')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(16),
                        
                        Forms\Components\Toggle::make('is_active')
                            ->label('Agent Actiu')
                            ->default(true),
                    ])->columns(2),
                
                Forms\Components\Section::make('Contacte')
                    ->schema([
                        Forms\Components\TextInput::make('telegram_username')
                            ->label('Usuari Telegram')
                            ->prefixIcon('heroicon-o-paper-airplane')
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->maxLength(255),
                        
                        Forms\Components\TextInput::make('phone')
                            ->label('Telèfon')
                            ->tel()
                            ->maxLength(255),
                    ])->columns(3),
                
                Forms\Components\Section::make('Informació Addicional')
                    ->schema([
                        Forms\Components\DatePicker::make('first_contact_date')
                            ->label('Data Primer Contacte')
                            ->native(false),
                        
                        Forms\Components\Textarea::make('notes')
                            ->label('Notes')
                            ->rows(4)
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('codename')
                    ->label('Agent')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                Tables\Columns\TextColumn::make('real_name')
                    ->label('Nom Real')
                    ->searchable()
                    ->toggleable(),
                
                Tables\Columns\BadgeColumn::make('current_faction')
                    ->label('Facció')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'resistance' => 'Resistència',
                        'enlightened' => 'Il·luminats',
                    })
                    ->colors([
                        'primary' => 'resistance',
                        'success' => 'enlightened',
                    ]),
                
                Tables\Columns\TextColumn::make('level')
                    ->label('Nivell')
                    ->sortable()
                    ->alignCenter(),
                
                Tables\Columns\TextColumn::make('telegram_username')
                    ->label('Telegram')
                    ->searchable()
                    ->toggleable()
                    ->icon('heroicon-o-paper-airplane'),
                
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Actiu')
                    ->boolean()
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('first_contact_date')
                    ->label('Primer Contacte')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Creat')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('current_faction')
                    ->label('Facció')
                    ->options([
                        'resistance' => 'Resistència',
                        'enlightened' => 'Il·luminats',
                    ]),
                
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Agent Actiu')
                    ->placeholder('Tots')
                    ->trueLabel('Només actius')
                    ->falseLabel('Només inactius'),
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
            ->defaultSort('codename');
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
            'index' => Pages\ListAgents::route('/'),
            'create' => Pages\CreateAgent::route('/create'),
            'edit' => Pages\EditAgent::route('/{record}/edit'),
        ];
    }
}
