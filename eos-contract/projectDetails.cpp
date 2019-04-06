#include <eosio/eosio.hpp>

using namespace eosio;

class [[eosio::contract]] projectDetails : public contract {
  public:
      using contract::contract;

      [[eosio::action]]
      void hi( name user ) {
         print( "Hello, ", user);
      }

      [[eosio::action]]
      void sendingRequest( name user ) {
         print( "Sending request, ", user);
      }

      [[eosio::action]]
      void voteSendingRequest( name user ) {
         print( "Vote sending request, ", user);
      }

      [[eosio::action]]
      void finalizeSendingRequest( name user ) {
         print( "Finalize sending request, ", user);
      }
};
