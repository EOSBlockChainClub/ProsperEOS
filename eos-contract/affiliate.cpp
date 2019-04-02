#include <eosio/eosio.hpp>

using namespace eosio;

class [[eosio::contract]] affiliate : public contract {
  public:
      using contract::contract;

      [[eosio::action]]
      void register( name user,  ) {
         print( "Hello, ", user);
      }
};
