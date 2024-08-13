<?php

namespace App\Filament\Resources\UnitResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\Summarizers\Sum;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;

class TransactionRelationManager extends RelationManager
{
    protected static string $relationship = 'transaction';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id')
                ->label('KODE TRANSAKSI')
                ->disabled()
                ->default(function () {
                    return IdGenerator::generate([
                        'table' => 'transactions',
                        'length' => 20,
                        'prefix' => 'TRK-'
                    ]);
                }),
    Forms\Components\DatePicker::make('tanggal_transaksi')
        ->required(),
    Forms\Components\Select::make('jenis_transaksi')
        ->Options([
            'DEBET'=>'DEBET',
            'KREDIT'=>'KREDIT',
        ])
        ->required(),
    Forms\Components\TextInput::make('quantity')
        ->required()
        ->numeric()
        ->reactive()
        ->afterStateUpdated(function ($state, $set, $get) {
            // Mengubah quantity menjadi integer
            $quantity = intval($state);
            $hargaSatuan = intval($get('harga_satuan')); // Ubah juga harga_satuan menjadi integer
    
            // Menghitung total
            $newTotal = $quantity * $hargaSatuan;
    
            // Set nilai total
            $set('total', $newTotal);
        }),
    Forms\Components\Select::make('satuan')
        ->Options([
            'Buah'=>'Buah',
            'Dus'=>'Dus',
            'Org'=>'Org',
            'Karung'=>'Karung',
            'Barang'=>'Barang',
        ])
        ->required(),
    Forms\Components\TextInput::make('harga_satuan')
        ->reactive()
        ->required()
        ->numeric()
        ->afterStateUpdated(function ($state, $set, $get) {
            // Mengubah harga_satuan menjadi integer
            $hargaSatuan = intval($state);
            $quantity = intval($get('quantity')); // Ubah juga quantity menjadi integer
    
            // Menghitung total
            $newTotal = $quantity * $hargaSatuan;
    
            // Set nilai total
            $set('total', $newTotal);
        }),
    Forms\Components\TextInput::make('total')
        ->required()
        ->numeric(),
            Forms\Components\Textarea::make('deskripsi')
        ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('total')
            ->columns([
                Tables\Columns\TextColumn::make('quantity'),
                Tables\Columns\TextColumn::make('harga_satuan'),
                Tables\Columns\TextColumn::make('jenis_transaksi'),
                Tables\Columns\TextColumn::make('deskripsi'),
     //           Tables\Columns\TextColumn::make('total'),

    
    
            
                Tables\Columns\TextColumn::make('total_debet')
                    ->label('Total Debet')
                    ->getStateUsing(fn($record) => $record->jenis_transaksi === 'DEBET' ? $record->total : 0)
                    ->summarize(
                        Summarizer::make()
                            ->using(fn($query) => $query->where('jenis_transaksi', 'DEBET')->sum('total'))
                            ->money('IDR')
                    ),
                    Tables\Columns\TextColumn::make('total_kredit')
                    ->label('Total Kredit')
                    ->getStateUsing(fn($record) => $record->jenis_transaksi === 'KREDIT' ? $record->total : 0)
                    ->summarize(
                        Summarizer::make()
                            ->using(fn($query) => $query->where('jenis_transaksi', 'KREDIT')->sum('total'))
                            ->money('IDR')
                    ),
                    Tables\Columns\TextColumn::make('total_profit')
                    ->label('Total Profit')
                    ->summarize(
                        Summarizer::make()
                            ->using(function($query) {
                                // Menghitung total debet dan kredit per unit
                                $unitId = $query->first()->unit_id; // Ambil unit_id dari query
                                
                                $totalDebet = DB::table('transactions')
                                    ->where('unit_id', $unitId)
                                    ->where('jenis_transaksi', 'DEBET')
                                    ->sum('total');
                                
                                $totalKredit = DB::table('transactions')
                                    ->where('unit_id', $unitId)
                                    ->where('jenis_transaksi', 'KREDIT')
                                    ->sum('total');
                                
                                // Hitung profit
                                return $totalDebet - $totalKredit;
                            })
                            ->money('IDR')
                    )
                    
                
                
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
