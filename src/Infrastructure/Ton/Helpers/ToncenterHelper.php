<?php declare(strict_types=1);

namespace Olifanton\DemoWallet\Infrastructure\Ton\Helpers;

use Olifanton\Ton\Marshalling\Json\EnsureJsonMapTrait;
use Olifanton\Ton\Transports\Toncenter\Responses as TcResponses;

final class ToncenterHelper
{
    use EnsureJsonMapTrait;

    /**
     * @throws \Throwable
     */
    public static function warmupHydratorCache(): void
    {
        $messages = [
            TcResponses\AccountState::class,
            TcResponses\AddressDetectionResult::class,
            TcResponses\Base64Address::class,
            TcResponses\BlockHeader::class,
            TcResponses\BlockIdExt::class,
            TcResponses\BlockTransactions::class,
            TcResponses\ConfigInfo::class,
            TcResponses\ConsensusBlock::class,
            TcResponses\ExtendedFullAccountState::class,
            TcResponses\Fees::class,
            TcResponses\FullAccountState::class,
            TcResponses\MasterchainInfo::class,
            TcResponses\Message::class,
            TcResponses\MessageData::class,
            TcResponses\QueryFees::class,
            TcResponses\Shards::class,
            TcResponses\ShortTxId::class,
            TcResponses\Transaction::class,
            TcResponses\TransactionId::class,
            TcResponses\TransactionsList::class,
            TcResponses\UnrecognizedSmcRunResult::class,
            TcResponses\WalletInformation::class,
        ];

        foreach ($messages as $messageClass) {
            self::ensureJsonMap($messageClass);
        }
    }
}
