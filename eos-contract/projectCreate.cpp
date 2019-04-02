#include <eosio/eosio.hpp>

using namespace eosio;

class [[eosio::contract]] projectCreate : public contract {
  public:
      using contract::contract;

      [[eosio::action]]
      void create( name name ) {
         print( "Project created, ", name);
      }
};
