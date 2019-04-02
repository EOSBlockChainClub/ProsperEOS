pkill -9 cleos
pkill -9 nodeos
pkill -9 keosd
rm ~/.local/share/eosio/nodeos/data/blocks/* -r
rm ~/.local/share/eosio/nodeos/data/state/* -r
nodeos --genesis-json genesis.json
cd /root
keosd &
nodeos -e -p eosio \
--plugin eosio::producer_plugin \
--plugin eosio::chain_api_plugin \
--plugin eosio::http_plugin \
--plugin eosio::history_plugin \
--plugin eosio::history_api_plugin \
--access-control-allow-origin='*' \
--contracts-console \
--http-validate-host=false \
--verbose-http-errors \
--filter-on='*' >> /root/nodeos.log 2>&1 &
