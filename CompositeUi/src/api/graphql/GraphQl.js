/*
 * This file is part of the Kreta package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

import {DefaultNetworkLayer} from 'react-relay';
import RelayMutationRequest from 'react-relay/lib/RelayMutationRequest';
import RelayQueryRequest from 'react-relay/lib/RelayQueryRequest';

import Config from './../../Config';

class GraphQl {
  constructor() {
    this.accessToken = () => (
      localStorage.token
    );

    this.baseUrl = () => (
      Config.taskManagerUrl
    );

    this.uri = () => (
      `${this.baseUrl()}?access_token=${this.accessToken()}`
    );

    this.isRelayQueryRequest = (query) => {
      if (!(query instanceof RelayQueryRequest)) {
        throw new TypeError('Given query must be a collection of RelayQueryRequest or a single RelayQueryRequest');
      }
    };

    this.isRelayMutationRequest = (mutation) => {
      if (!(mutation instanceof RelayMutationRequest)) {
        throw new TypeError('Given mutation must be a RelayMutationRequest');
      }
    };

    this.relayNetworkLayer = new DefaultNetworkLayer(this.uri());
  }

  query(query) {
    if (query instanceof Array) {
      for (const variable of query) {
        this.isRelayQueryRequest(variable);
      }
    } else {
      this.isRelayQueryRequest(query);
      query = [query];
    }

    return this.relayNetworkLayer.sendQueries(query);
  }

  mutation(mutation) {
    this.isRelayMutationRequest(mutation);

    return this.relayNetworkLayer.sendMutation(mutation);
  }
}

const GraphQlInstance = new GraphQl();

export default GraphQlInstance;
