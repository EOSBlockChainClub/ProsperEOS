#include <eosio/eosio.hpp>

using namespace eosio;

class [[eosio::contract]] peos3 : public contract {
  public:
      using contract::contract;

      [[eosio::action]]
      void create( name user ) {
         print("PEOS: Project created.");
      }

      [[eosio::action]]
      void request( name user ) {
         print("PEOS: Sending request created.");
      }

      [[eosio::action]]
      void voteyes( name user ) {
         print("PEOS: Voted on sending request as YES.");
      }

      [[eosio::action]]
      void voteno( name user ) {
         print("PEOS: Voted on sending request as NO.");
      }

      [[eosio::action]]
      void finalize( name user ) {
         print("PEOS: Sending request finalize. Payment sent to supplier.");
      }

      [[eosio::action]]
      void supplier( name user ) {
         print("PEOS: Supplier added.");
      }

      [[eosio::action]]
      void affiliate( name user ) {
         print("PEOS: Affiliate account created.");
      }

      [[eosio::action]]
      void contribute( name user ) {
         print("PEOS: contributed.");
      }
};
