cd /var/www/dish2019.gigamike.net/eos-contract
unlink project.wasm
unlink project.abi
eosio-cpp -abigen -o project.wasm project.cpp
